import pandas as pd
import numpy as np
from sqlalchemy import create_engine, text
from sklearn.cluster import KMeans

# =========================================================
# KONFIGURASI DATABASE
# =========================================================

DB_URI = "mysql+pymysql://root:@127.0.0.1:3306/trenparpol"
engine = create_engine(DB_URI)

# =========================================================
# QUERY DATASET ANALISIS
# Unit analisis:
# kecamatan × partai yang comparable pada 2019 dan 2024
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

print()
print("Jumlah observasi :", len(df))
print("Jumlah kolom     :", len(df.columns))

if len(df) != 70:
    raise ValueError(
        f"Dataset tidak sesuai. Seharusnya 70 observasi, "
        f"tetapi ditemukan {len(df)}."
    )

# =========================================================
# FITUR UTAMA
# A = share_2019 + share_2024
# Tidak menggunakan scaling
# =========================================================

X = df[["share_2019", "share_2024"]].copy()

# =========================================================
# FUNGSI PROFILING
# =========================================================

def profiling_k(k):
    print()
    print("=" * 70)
    print(f"PROFILING K = {k}")
    print("=" * 70)

    model = KMeans(
        n_clusters=k,
        random_state=42,
        n_init=50
    )

    df_hasil = df.copy()

    df_hasil["cluster"] = model.fit_predict(X)

    # -----------------------------------------------------
    # Centroid dalam skala asli
    # -----------------------------------------------------

    centroid = pd.DataFrame(
        model.cluster_centers_,
        columns=["share_2019", "share_2024"]
    )

    centroid["cluster"] = centroid.index

    # -----------------------------------------------------
    # Profil tiap cluster berdasarkan rata-rata aktual
    # -----------------------------------------------------

    profil = (
        df_hasil
        .groupby("cluster")
        .agg(
            jumlah_observasi=("cluster", "size"),
            mean_share_2019=("share_2019", "mean"),
            mean_share_2024=("share_2024", "mean"),
            min_share_2019=("share_2019", "min"),
            max_share_2019=("share_2019", "max"),
            min_share_2024=("share_2024", "min"),
            max_share_2024=("share_2024", "max")
        )
        .reset_index()
    )

    # -----------------------------------------------------
    # Tambahkan rata-rata gabungan untuk membantu
    # menentukan level cluster
    # -----------------------------------------------------

    profil["mean_level"] = (
        profil["mean_share_2019"] +
        profil["mean_share_2024"]
    ) / 2

    profil = profil.sort_values(
        "mean_level",
        ascending=False
    ).reset_index(drop=True)

    # -----------------------------------------------------
    # Beri ranking berdasarkan level
    # BUKAN berdasarkan nomor cluster asli K-Means
    # -----------------------------------------------------

    if k == 3:
        label = ["Tinggi", "Sedang", "Rendah"]

        profil["kategori"] = label

    elif k == 2:
        label = ["Tinggi", "Rendah"]

        profil["kategori"] = label

    else:
        profil["kategori"] = ""

    # -----------------------------------------------------
    # Tampilkan centroid
    # -----------------------------------------------------

    print()
    print("CENTROID K-MEANS:")
    print(
        centroid[
            ["cluster", "share_2019", "share_2024"]
        ].to_string(index=False)
    )

    # -----------------------------------------------------
    # Tampilkan profil cluster
    # -----------------------------------------------------

    print()
    print("PROFIL CLUSTER:")
    print(
        profil[
            [
                "cluster",
                "kategori",
                "jumlah_observasi",
                "mean_share_2019",
                "mean_share_2024",
                "mean_level"
            ]
        ].to_string(index=False)
    )

    # -----------------------------------------------------
    # Tampilkan anggota tiap cluster
    # -----------------------------------------------------

    print()
    print("JUMLAH ANGGOTA PER CLUSTER:")

    for _, row in profil.iterrows():
        print(
            f"Cluster {int(row['cluster'])} "
            f"({row['kategori']}): "
            f"{int(row['jumlah_observasi'])} observasi"
        )

    return df_hasil, profil


# =========================================================
# PROFILING K=2
# =========================================================

hasil_k2, profil_k2 = profiling_k(2)

# =========================================================
# PROFILING K=3
# =========================================================

hasil_k3, profil_k3 = profiling_k(3)

# =========================================================
# SIMPAN HASIL PROFILING
# =========================================================

profil_k2.to_csv(
    "profiling_k2.csv",
    index=False
)

profil_k3.to_csv(
    "profiling_k3.csv",
    index=False
)

hasil_k2.to_csv(
    "anggota_cluster_k2.csv",
    index=False
)

hasil_k3.to_csv(
    "anggota_cluster_k3.csv",
    index=False
)

print()
print("=" * 70)
print("HASIL PROFILING SELESAI")
print("=" * 70)

print("File yang dibuat:")
print("- profiling_k2.csv")
print("- profiling_k3.csv")
print("- anggota_cluster_k2.csv")
print("- anggota_cluster_k3.csv")