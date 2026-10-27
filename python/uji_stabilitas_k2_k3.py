import pandas as pd
import numpy as np
from sqlalchemy import create_engine, text

from sklearn.cluster import KMeans
from sklearn.metrics import (
    silhouette_score,
    silhouette_samples,
    adjusted_rand_score
)


# =========================================================
# KONFIGURASI DATABASE
# =========================================================

DB_URI = "mysql+pymysql://root:@127.0.0.1:3306/trenparpol"

engine = create_engine(DB_URI)


# =========================================================
# QUERY DATASET
# =========================================================

query = """
WITH
suara_2019 AS (
    SELECT
        id_kecamatan,
        id_partai,
        SUM(jumlah_suara) AS suara_2019
    FROM master_suara_partai_2019
    GROUP BY id_kecamatan, id_partai
),

suara_2024 AS (
    SELECT
        id_kecamatan,
        id_partai,
        SUM(jumlah_suara) AS suara_2024
    FROM master_suara_partai_2024
    GROUP BY id_kecamatan, id_partai
),

total_2019 AS (
    SELECT
        id_kecamatan,
        SUM(jumlah_suara) AS total_kecamatan_2019
    FROM master_suara_partai_2019
    GROUP BY id_kecamatan
),

total_2024 AS (
    SELECT
        id_kecamatan,
        SUM(jumlah_suara) AS total_kecamatan_2024
    FROM master_suara_partai_2024
    GROUP BY id_kecamatan
),

data_gabungan AS (
    SELECT
        s19.id_kecamatan,
        s19.id_partai,
        s19.suara_2019,
        s24.suara_2024,
        t19.total_kecamatan_2019,
        t24.total_kecamatan_2024
    FROM suara_2019 s19

    INNER JOIN suara_2024 s24
        ON s19.id_kecamatan = s24.id_kecamatan
        AND s19.id_partai = s24.id_partai

    INNER JOIN total_2019 t19
        ON s19.id_kecamatan = t19.id_kecamatan

    INNER JOIN total_2024 t24
        ON s24.id_kecamatan = t24.id_kecamatan
)

SELECT
    id_kecamatan,
    id_partai,
    suara_2019,
    suara_2024,

    (suara_2019 / total_kecamatan_2019) * 100
        AS share_2019,

    (suara_2024 / total_kecamatan_2024) * 100
        AS share_2024

FROM data_gabungan

ORDER BY id_kecamatan, id_partai
"""


# =========================================================
# AMBIL DATA
# =========================================================

print("Mengambil dataset dari MySQL...")

df = pd.read_sql(text(query), engine)


# =========================================================
# VALIDASI DATASET
# =========================================================

print()
print("=" * 70)
print("VALIDASI DATASET")
print("=" * 70)

print(f"Jumlah observasi : {len(df)}")

if len(df) != 70:
    raise ValueError(
        f"Dataset tidak sesuai. "
        f"Seharusnya 70 observasi, tetapi ditemukan {len(df)}."
    )


# =========================================================
# FITUR K-MEANS
# =========================================================

X = df[
    [
        "share_2019",
        "share_2024"
    ]
].copy()


# =========================================================
# SEED YANG DIGUNAKAN
# =========================================================

seeds = [
    0,
    1,
    2,
    3,
    4,
    10,
    20,
    42,
    50,
    100
]


# =========================================================
# FUNGSI UJI STABILITAS
# =========================================================

def uji_stabilitas(k):

    print()
    print("=" * 70)
    print(f"STABILITAS K = {k}")
    print("=" * 70)

    semua_label = []
    hasil = []

    for seed in seeds:

        model = KMeans(
            n_clusters=k,
            random_state=seed,
            n_init=50
        )

        labels = model.fit_predict(X)

        silhouette = silhouette_score(
            X,
            labels
        )

        semua_label.append(labels)

        hasil.append({
            "seed": seed,
            "silhouette": silhouette
        })

        print(
            f"Seed {seed:>3} | "
            f"Silhouette = {silhouette:.4f}"
        )

    # -----------------------------------------------------
    # Bandingkan semua hasil terhadap hasil seed pertama
    # -----------------------------------------------------

    referensi = semua_label[0]

    ari_hasil = []

    for i in range(1, len(semua_label)):

        ari = adjusted_rand_score(
            referensi,
            semua_label[i]
        )

        ari_hasil.append(ari)

    print()
    print("ARI terhadap seed pertama:")

    for i, seed in enumerate(seeds[1:]):

        print(
            f"Seed {seed:>3} | "
            f"ARI = {ari_hasil[i]:.4f}"
        )

    print()
    print(
        f"ARI minimum : {min(ari_hasil):.4f}"
    )

    print(
        f"ARI maksimum : {max(ari_hasil):.4f}"
    )

    print(
        f"ARI rata-rata: {np.mean(ari_hasil):.4f}"
    )

    # -----------------------------------------------------
    # Label final menggunakan seed 42
    # -----------------------------------------------------

    model_final = KMeans(
        n_clusters=k,
        random_state=42,
        n_init=50
    )

    label_final = model_final.fit_predict(X)

    df_hasil = df.copy()

    df_hasil["cluster"] = label_final

    # -----------------------------------------------------
    # Silhouette per observasi
    # -----------------------------------------------------

    nilai_silhouette = silhouette_samples(
        X,
        label_final
    )

    df_hasil["silhouette_individu"] = nilai_silhouette

    # -----------------------------------------------------
    # Profil silhouette tiap cluster
    # -----------------------------------------------------

    profil = (
        df_hasil
        .groupby("cluster")
        .agg(
            jumlah_observasi=("cluster", "size"),
            mean_silhouette=(
                "silhouette_individu",
                "mean"
            ),
            min_silhouette=(
                "silhouette_individu",
                "min"
            ),
            jumlah_negatif=(
                "silhouette_individu",
                lambda x: (x < 0).sum()
            ),
            jumlah_mendekati_nol=(
                "silhouette_individu",
                lambda x: (x < 0.10).sum()
            )
        )
        .reset_index()
    )

    print()
    print("PROFIL SILHOUETTE PER CLUSTER:")

    print(
        profil.to_string(
            index=False,
            float_format=lambda x: f"{x:.4f}"
        )
    )

    # -----------------------------------------------------
    # Ringkasan keseluruhan
    # -----------------------------------------------------

    jumlah_negatif_total = (
        df_hasil["silhouette_individu"] < 0
    ).sum()

    jumlah_mendekati_nol_total = (
        df_hasil["silhouette_individu"] < 0.10
    ).sum()

    print()
    print("RINGKASAN SILHOUETTE INDIVIDU:")

    print(
        f"Minimum silhouette : "
        f"{df_hasil['silhouette_individu'].min():.4f}"
    )

    print(
        f"Maksimum silhouette: "
        f"{df_hasil['silhouette_individu'].max():.4f}"
    )

    print(
        f"Rata-rata silhouette: "
        f"{df_hasil['silhouette_individu'].mean():.4f}"
    )

    print(
        f"Observasi negatif   : "
        f"{jumlah_negatif_total}"
    )

    print(
        f"Observasi < 0.10    : "
        f"{jumlah_mendekati_nol_total}"
    )

    return {
        "hasil_seed": hasil,
        "profil": profil,
        "data": df_hasil,
        "ari": ari_hasil
    }


# =========================================================
# UJI K=2
# =========================================================

hasil_k2 = uji_stabilitas(2)


# =========================================================
# UJI K=3
# =========================================================

hasil_k3 = uji_stabilitas(3)


# =========================================================
# SIMPAN HASIL
# =========================================================

pd.DataFrame(
    hasil_k2["hasil_seed"]
).to_csv(
    "stabilitas_k2.csv",
    index=False
)

pd.DataFrame(
    hasil_k3["hasil_seed"]
).to_csv(
    "stabilitas_k3.csv",
    index=False
)

hasil_k2["profil"].to_csv(
    "silhouette_cluster_k2.csv",
    index=False
)

hasil_k3["profil"].to_csv(
    "silhouette_cluster_k3.csv",
    index=False
)

hasil_k2["data"].to_csv(
    "silhouette_individu_k2.csv",
    index=False
)

hasil_k3["data"].to_csv(
    "silhouette_individu_k3.csv",
    index=False
)


# =========================================================
# PERBANDINGAN AKHIR
# =========================================================

ringkasan = pd.DataFrame([
    {
        "K": 2,
        "ARI_min": min(hasil_k2["ari"]),
        "ARI_max": max(hasil_k2["ari"]),
        "ARI_mean": np.mean(hasil_k2["ari"]),
        "Silhouette_mean": hasil_k2["data"][
            "silhouette_individu"
        ].mean(),
        "Silhouette_min": hasil_k2["data"][
            "silhouette_individu"
        ].min(),
        "Observasi_negatif": (
            hasil_k2["data"][
                "silhouette_individu"
            ] < 0
        ).sum()
    },
    {
        "K": 3,
        "ARI_min": min(hasil_k3["ari"]),
        "ARI_max": max(hasil_k3["ari"]),
        "ARI_mean": np.mean(hasil_k3["ari"]),
        "Silhouette_mean": hasil_k3["data"][
            "silhouette_individu"
        ].mean(),
        "Silhouette_min": hasil_k3["data"][
            "silhouette_individu"
        ].min(),
        "Observasi_negatif": (
            hasil_k3["data"][
                "silhouette_individu"
            ] < 0
        ).sum()
    }
])

print()
print("=" * 70)
print("RINGKASAN STABILITAS K=2 VS K=3")
print("=" * 70)

print(
    ringkasan.to_string(
        index=False,
        float_format=lambda x: f"{x:.4f}"
    )
)

ringkasan.to_csv(
    "ringkasan_stabilitas_k2_k3.csv",
    index=False
)


# =========================================================
# SELESAI
# =========================================================

print()
print("=" * 70)
print("UJI STABILITAS SELESAI")
print("=" * 70)

print("File yang dibuat:")
print("- stabilitas_k2.csv")
print("- stabilitas_k3.csv")
print("- silhouette_cluster_k2.csv")
print("- silhouette_cluster_k3.csv")
print("- silhouette_individu_k2.csv")
print("- silhouette_individu_k3.csv")
print("- ringkasan_stabilitas_k2_k3.csv")