import pandas as pd


# =========================================================
# BACA HASIL K=3
# =========================================================

file_input = "silhouette_individu_k3.csv"

df = pd.read_csv(file_input)


# =========================================================
# MAPPING KATEGORI
# Berdasarkan rata-rata share cluster:
# Tinggi > Sedang > Rendah
# =========================================================

profil = (
    df.groupby("cluster")
    .agg(
        mean_share_2019=("share_2019", "mean"),
        mean_share_2024=("share_2024", "mean"),
        jumlah_observasi=("cluster", "size")
    )
    .reset_index()
)

profil["mean_level"] = (
    profil["mean_share_2019"] +
    profil["mean_share_2024"]
) / 2

profil = profil.sort_values(
    "mean_level",
    ascending=False
).reset_index(drop=True)

kategori = ["Tinggi", "Sedang", "Rendah"]

profil["kategori"] = kategori


# =========================================================
# GABUNGKAN KATEGORI KE DATA
# =========================================================

df = df.merge(
    profil[
        [
            "cluster",
            "kategori"
        ]
    ],
    on="cluster",
    how="left"
)


# =========================================================
# PROFIL KESELURUHAN
# =========================================================

print()
print("=" * 80)
print("PROFIL KOMPOSISI K=3")
print("=" * 80)

print()

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
    ].to_string(
        index=False,
        float_format=lambda x: f"{x:.4f}"
    )
)


# =========================================================
# KOMPOSISI BERDASARKAN KECAMATAN
# =========================================================

print()
print("=" * 80)
print("KOMPOSISI CLUSTER BERDASARKAN KECAMATAN")
print("=" * 80)

tabel_kecamatan = pd.crosstab(
    df["id_kecamatan"],
    df["kategori"]
)

# Pastikan urutan kolom
for kolom in ["Tinggi", "Sedang", "Rendah"]:
    if kolom not in tabel_kecamatan.columns:
        tabel_kecamatan[kolom] = 0

tabel_kecamatan = tabel_kecamatan[
    ["Tinggi", "Sedang", "Rendah"]
]

print()
print(tabel_kecamatan)


# =========================================================
# KOMPOSISI BERDASARKAN PARTAI
# =========================================================

print()
print("=" * 80)
print("KOMPOSISI CLUSTER BERDASARKAN PARTAI")
print("=" * 80)

tabel_partai = pd.crosstab(
    df["id_partai"],
    df["kategori"]
)

for kolom in ["Tinggi", "Sedang", "Rendah"]:
    if kolom not in tabel_partai.columns:
        tabel_partai[kolom] = 0

tabel_partai = tabel_partai[
    ["Tinggi", "Sedang", "Rendah"]
]

print()
print(tabel_partai)


# =========================================================
# DAFTAR OBSERVASI SETIAP KATEGORI
# =========================================================

for kategori_nama in [
    "Tinggi",
    "Sedang",
    "Rendah"
]:

    print()
    print("=" * 80)
    print(f"OBSERVASI KATEGORI {kategori_nama.upper()}")
    print("=" * 80)

    data = df[
        df["kategori"] == kategori_nama
    ].sort_values(
        [
            "share_2019",
            "share_2024"
        ],
        ascending=False
    )

    print()

    print(
        data[
            [
                "id_kecamatan",
                "id_partai",
                "share_2019",
                "share_2024",
                "silhouette_individu"
            ]
        ].to_string(
            index=False,
            float_format=lambda x: f"{x:.4f}"
        )
    )


# =========================================================
# OBSERVASI DENGAN SILHOUETTE TERENDAH
# =========================================================

print()
print("=" * 80)
print("OBSERVASI DENGAN SILHOUETTE TERENDAH")
print("=" * 80)

print()

print(
    df[
        [
            "id_kecamatan",
            "id_partai",
            "kategori",
            "share_2019",
            "share_2024",
            "silhouette_individu"
        ]
    ]
    .sort_values(
        "silhouette_individu"
    )
    .head(10)
    .to_string(
        index=False,
        float_format=lambda x: f"{x:.4f}"
    )
)


# =========================================================
# SIMPAN HASIL
# =========================================================

df.to_csv(
    "komposisi_cluster_k3.csv",
    index=False
)

tabel_kecamatan.to_csv(
    "komposisi_k3_per_kecamatan.csv"
)

tabel_partai.to_csv(
    "komposisi_k3_per_partai.csv"
)

print()
print("=" * 80)
print("PEMERIKSAAN KOMPOSISI SELESAI")
print("=" * 80)

print("File yang dibuat:")
print("- komposisi_cluster_k3.csv")
print("- komposisi_k3_per_kecamatan.csv")
print("- komposisi_k3_per_partai.csv")