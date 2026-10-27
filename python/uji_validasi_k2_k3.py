import pandas as pd
import numpy as np
from sqlalchemy import create_engine, text

from sklearn.cluster import KMeans
from sklearn.metrics import (
    silhouette_score,
    davies_bouldin_score,
    calinski_harabasz_score
)

import matplotlib.pyplot as plt


# =========================================================
# KONFIGURASI DATABASE
# =========================================================

DB_URI = "mysql+pymysql://root:@127.0.0.1:3306/trenparpol"

engine = create_engine(DB_URI)


# =========================================================
# QUERY DATASET
# Unit analisis:
# kecamatan × partai comparable
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
print(f"Jumlah kolom     : {len(df.columns)}")

if len(df) != 70:
    raise ValueError(
        f"Dataset tidak sesuai. "
        f"Seharusnya 70 observasi, tetapi ditemukan {len(df)}."
    )

if df[["share_2019", "share_2024"]].isnull().any().any():
    raise ValueError("Terdapat nilai NULL pada fitur clustering.")

if (df[["share_2019", "share_2024"]] < 0).any().any():
    raise ValueError("Terdapat nilai negatif pada fitur clustering.")


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
# VALIDASI K=2 DAN K=3
# =========================================================

hasil_validasi = []

hasil_cluster = {}


for k in [2, 3]:

    print()
    print("=" * 70)
    print(f"VALIDASI K = {k}")
    print("=" * 70)

    model = KMeans(
        n_clusters=k,
        random_state=42,
        n_init=50
    )

    labels = model.fit_predict(X)

    silhouette = silhouette_score(
        X,
        labels
    )

    davies_bouldin = davies_bouldin_score(
        X,
        labels
    )

    calinski_harabasz = calinski_harabasz_score(
        X,
        labels
    )

    inertia = model.inertia_

    hasil_validasi.append({
        "K": k,
        "Silhouette": silhouette,
        "Davies_Bouldin": davies_bouldin,
        "Calinski_Harabasz": calinski_harabasz,
        "Inertia": inertia
    })

    df_cluster = df.copy()

    df_cluster["cluster"] = labels

    hasil_cluster[k] = df_cluster

    print()
    print(f"Silhouette Score       : {silhouette:.4f}")
    print(f"Davies-Bouldin Index   : {davies_bouldin:.4f}")
    print(f"Calinski-Harabasz      : {calinski_harabasz:.4f}")
    print(f"Inertia                : {inertia:.4f}")

    print()
    print("Jumlah anggota cluster:")

    jumlah_cluster = (
        pd.Series(labels)
        .value_counts()
        .sort_index()
    )

    for cluster, jumlah in jumlah_cluster.items():
        print(
            f"Cluster {cluster}: "
            f"{jumlah} observasi"
        )


# =========================================================
# TABEL PERBANDINGAN
# =========================================================

df_validasi = pd.DataFrame(
    hasil_validasi
)

print()
print("=" * 70)
print("PERBANDINGAN K=2 DAN K=3")
print("=" * 70)

print(
    df_validasi.to_string(
        index=False,
        float_format=lambda x: f"{x:.4f}"
    )
)


# =========================================================
# MENENTUKAN PEMENANG SETIAP METRIK
# =========================================================

best_silhouette = df_validasi.loc[
    df_validasi["Silhouette"].idxmax()
]

best_davies = df_validasi.loc[
    df_validasi["Davies_Bouldin"].idxmin()
]

best_calinski = df_validasi.loc[
    df_validasi["Calinski_Harabasz"].idxmax()
]


print()
print("=" * 70)
print("HASIL PEMILIHAN BERDASARKAN METRIK")
print("=" * 70)

print(
    f"Silhouette tertinggi       : "
    f"K={int(best_silhouette['K'])} "
    f"({best_silhouette['Silhouette']:.4f})"
)

print(
    f"Davies-Bouldin terendah    : "
    f"K={int(best_davies['K'])} "
    f"({best_davies['Davies_Bouldin']:.4f})"
)

print(
    f"Calinski-Harabasz tertinggi: "
    f"K={int(best_calinski['K'])} "
    f"({best_calinski['Calinski_Harabasz']:.4f})"
)


# =========================================================
# SIMPAN HASIL VALIDASI
# =========================================================

df_validasi.to_csv(
    "hasil_validasi_k2_k3.csv",
    index=False
)


# =========================================================
# SCATTER PLOT K=2
# =========================================================

df_k2 = hasil_cluster[2]

plt.figure(figsize=(9, 7))

for cluster in sorted(df_k2["cluster"].unique()):

    data_cluster = df_k2[
        df_k2["cluster"] == cluster
    ]

    plt.scatter(
        data_cluster["share_2019"],
        data_cluster["share_2024"],
        label=f"Cluster {cluster}"
    )


# Garis diagonal y=x
min_value = min(
    X["share_2019"].min(),
    X["share_2024"].min()
)

max_value = max(
    X["share_2019"].max(),
    X["share_2024"].max()
)

plt.plot(
    [min_value, max_value],
    [min_value, max_value],
    linestyle="--",
    label="Garis perubahan tetap"
)

plt.xlabel("Share 2019 (%)")
plt.ylabel("Share 2024 (%)")
plt.title("K-Means K=2: Share 2019 vs Share 2024")
plt.legend()
plt.grid(True, alpha=0.3)

plt.tight_layout()

plt.savefig(
    "scatter_k2.png",
    dpi=300
)

plt.show()


# =========================================================
# SCATTER PLOT K=3
# =========================================================

df_k3 = hasil_cluster[3]

plt.figure(figsize=(9, 7))

for cluster in sorted(df_k3["cluster"].unique()):

    data_cluster = df_k3[
        df_k3["cluster"] == cluster
    ]

    plt.scatter(
        data_cluster["share_2019"],
        data_cluster["share_2024"],
        label=f"Cluster {cluster}"
    )


plt.plot(
    [min_value, max_value],
    [min_value, max_value],
    linestyle="--",
    label="Garis perubahan tetap"
)

plt.xlabel("Share 2019 (%)")
plt.ylabel("Share 2024 (%)")
plt.title("K-Means K=3: Share 2019 vs Share 2024")
plt.legend()
plt.grid(True, alpha=0.3)

plt.tight_layout()

plt.savefig(
    "scatter_k3.png",
    dpi=300
)

plt.show()


# =========================================================
# SIMPAN DATA CLUSTER
# =========================================================

hasil_cluster[2].to_csv(
    "anggota_cluster_k2_validasi.csv",
    index=False
)

hasil_cluster[3].to_csv(
    "anggota_cluster_k3_validasi.csv",
    index=False
)


# =========================================================
# SELESAI
# =========================================================

print()
print("=" * 70)
print("VALIDASI SELESAI")
print("=" * 70)

print("File yang dibuat:")
print("- hasil_validasi_k2_k3.csv")
print("- scatter_k2.png")
print("- scatter_k3.png")
print("- anggota_cluster_k2_validasi.csv")
print("- anggota_cluster_k3_validasi.csv")