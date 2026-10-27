import pandas as pd
import numpy as np

from sqlalchemy import create_engine, text
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import silhouette_score


# ============================================================
# KONFIGURASI DATABASE
# ============================================================

DB_URI = "mysql+pymysql://root:@127.0.0.1:3306/trenparpol"

engine = create_engine(DB_URI)


# ============================================================
# QUERY DATASET 70 OBSERVASI
# ============================================================

QUERY = """
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
        SUM(jumlah_suara) AS total_2019
    FROM master_suara_partai_2019
    GROUP BY id_kecamatan
),

total_2024 AS (
    SELECT
        id_kecamatan,
        SUM(jumlah_suara) AS total_2024
    FROM master_suara_partai_2024
    GROUP BY id_kecamatan
),

dataset AS (
    SELECT
        s19.id_kecamatan,
        s19.id_partai,

        (s19.suara_2019 / NULLIF(t19.total_2019, 0)) * 100
            AS share_2019,

        (s24.suara_2024 / NULLIF(t24.total_2024, 0)) * 100
            AS share_2024

    FROM suara_2019 s19

    INNER JOIN suara_2024 s24
        ON s24.id_kecamatan = s19.id_kecamatan
        AND s24.id_partai = s19.id_partai

    INNER JOIN total_2019 t19
        ON t19.id_kecamatan = s19.id_kecamatan

    INNER JOIN total_2024 t24
        ON t24.id_kecamatan = s24.id_kecamatan

    WHERE s19.id_partai IN (
        1, 2, 3, 4, 5, 6, 8,
        9, 10, 11, 12, 13, 14, 15
    )
)

SELECT
    id_kecamatan,
    id_partai,
    share_2019,
    share_2024
FROM dataset
ORDER BY id_kecamatan, id_partai;
"""


# ============================================================
# AMBIL DATA DARI MYSQL
# ============================================================

print("=" * 70)
print("UJI SCALING DAN JUMLAH CLUSTER K-MEANS")
print("=" * 70)

print("\nMengambil dataset dari MySQL...")

with engine.connect() as connection:
    df = pd.read_sql(text(QUERY), connection)


# ============================================================
# VALIDASI DATASET
# ============================================================

print("\nValidasi dataset:")

print(f"Jumlah observasi : {len(df)}")
print(f"Jumlah kolom     : {len(df.columns)}")

if len(df) != 70:
    raise ValueError(
        f"Dataset tidak sesuai. Diharapkan 70 observasi, "
        f"tetapi ditemukan {len(df)}."
    )

if df[["share_2019", "share_2024"]].isnull().any().any():
    raise ValueError(
        "Terdapat nilai NULL pada share_2019 atau share_2024."
    )

if not np.isfinite(
    df[["share_2019", "share_2024"]].to_numpy()
).all():
    raise ValueError(
        "Terdapat nilai tidak valid/infinite pada fitur."
    )


# ============================================================
# STATISTIK DESKRIPTIF
# ============================================================

print("\n" + "=" * 70)
print("STATISTIK FITUR")
print("=" * 70)

statistik = df[["share_2019", "share_2024"]].agg(
    ["mean", "std", "min", "max"]
).T

print(statistik.to_string(float_format=lambda x: f"{x:.4f}"))


# ============================================================
# FITUR K-MEANS
# ============================================================

FEATURES = ["share_2019", "share_2024"]

X_original = df[FEATURES].astype(float).to_numpy()


# ============================================================
# STANDARDISASI
# ============================================================

scaler = StandardScaler()

X_scaled = scaler.fit_transform(X_original)


# ============================================================
# FUNGSI PENGUJIAN
# ============================================================

def jalankan_kmeans(X, nama_preprocessing, k):
    """
    Menjalankan K-Means untuk satu preprocessing dan satu nilai K.
    """

    model = KMeans(
        n_clusters=k,
        random_state=42,
        n_init=50
    )

    labels = model.fit_predict(X)

    silhouette = silhouette_score(X, labels)

    jumlah_cluster = np.bincount(labels)

    return {
        "preprocessing": nama_preprocessing,
        "k": k,
        "silhouette": silhouette,
        "inertia": model.inertia_,
        "cluster_0": int(jumlah_cluster[0]),
        "cluster_1": int(jumlah_cluster[1]),
        "cluster_2": int(jumlah_cluster[2]) if k >= 3 else None,
        "cluster_3": int(jumlah_cluster[3]) if k >= 4 else None,
        "cluster_4": int(jumlah_cluster[4]) if k >= 5 else None,
        "model": model,
        "labels": labels
    }


# ============================================================
# UJI K = 2, 3, 4, 5
# ============================================================

hasil = []

K_VALUES = [2, 3, 4, 5]


print("\n" + "=" * 70)
print("MENJALANKAN EKSPERIMEN")
print("=" * 70)

for nama_preprocessing, X in [
    ("Tanpa Scaling", X_original),
    ("StandardScaler", X_scaled)
]:

    print(f"\n>>> {nama_preprocessing}")

    for k in K_VALUES:

        hasil_k = jalankan_kmeans(
            X,
            nama_preprocessing,
            k
        )

        hasil.append(hasil_k)

        print(
            f"K={k} | "
            f"Silhouette={hasil_k['silhouette']:.4f} | "
            f"Inertia={hasil_k['inertia']:.4f}"
        )


# ============================================================
# TABEL RINGKASAN HASIL
# ============================================================

df_hasil = pd.DataFrame([
    {
        "Preprocessing": item["preprocessing"],
        "K": item["k"],
        "Silhouette": item["silhouette"],
        "Inertia": item["inertia"],
        "Cluster_1": item["cluster_0"],
        "Cluster_2": item["cluster_1"],
        "Cluster_3": item["cluster_2"],
        "Cluster_4": item["cluster_3"],
        "Cluster_5": item["cluster_4"]
    }
    for item in hasil
])


print("\n" + "=" * 70)
print("HASIL LENGKAP")
print("=" * 70)

print(
    df_hasil.to_string(
        index=False,
        float_format=lambda x: f"{x:.4f}"
    )
)


# ============================================================
# HASIL TERBAIK PER PREPROCESSING
# ============================================================

print("\n" + "=" * 70)
print("SILHOUETTE TERTINGGI PER PREPROCESSING")
print("=" * 70)

for preprocessing in ["Tanpa Scaling", "StandardScaler"]:

    subset = df_hasil[
        df_hasil["Preprocessing"] == preprocessing
    ]

    terbaik = subset.loc[
        subset["Silhouette"].idxmax()
    ]

    print(
        f"{preprocessing}: "
        f"K={int(terbaik['K'])}, "
        f"Silhouette={terbaik['Silhouette']:.4f}"
    )


# ============================================================
# PERBANDINGAN K=3
# ============================================================

print("\n" + "=" * 70)
print("PERBANDINGAN K=3")
print("=" * 70)

for preprocessing in ["Tanpa Scaling", "StandardScaler"]:

    subset = df_hasil[
        (df_hasil["Preprocessing"] == preprocessing)
        & (df_hasil["K"] == 3)
    ]

    baris = subset.iloc[0]

    print(
        f"{preprocessing}: "
        f"Silhouette={baris['Silhouette']:.4f}, "
        f"Cluster="
        f"[{int(baris['Cluster_1'])}, "
        f"{int(baris['Cluster_2'])}, "
        f"{int(baris['Cluster_3'])}]"
    )


# ============================================================
# SIMPAN HASIL
# ============================================================

OUTPUT_FILE = "hasil_uji_scaling_kmeans.csv"

df_hasil.to_csv(
    OUTPUT_FILE,
    index=False
)

print("\n" + "=" * 70)
print(f"Hasil pengujian disimpan ke: {OUTPUT_FILE}")
print("=" * 70)