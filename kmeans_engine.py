import sys
import json
import pandas as pd
import numpy as np

from sklearn.cluster import KMeans
from sklearn.metrics import silhouette_score


# ============================================================
# KONFIGURASI
# ============================================================

JUMLAH_CLUSTER = 2
RANDOM_STATE = 42
N_INIT = 50

JUMLAH_OBSERVASI_MINIMAL = 2


# ============================================================
# FUNGSI BANTUAN
# ============================================================

def kirim_error(pesan):
    """
    Mengirim error dalam format JSON agar dapat dibaca
    oleh Laravel Controller.
    """
    print(
        json.dumps(
            {
                "error": str(pesan)
            },
            ensure_ascii=False
        )
    )


def validasi_input(df):
    """
    Validasi struktur dan isi dataset sebelum K-Means.
    """

    kolom_wajib = [
        "id_kecamatan",
        "id_partai",
        "share_2019",
        "share_2024"
    ]

    kolom_hilang = [
        kolom
        for kolom in kolom_wajib
        if kolom not in df.columns
    ]

    if kolom_hilang:
        raise ValueError(
            "Kolom wajib tidak ditemukan: "
            + ", ".join(kolom_hilang)
        )

    if df.empty:
        raise ValueError(
            "Dataset kosong."
        )

    if len(df) != 70:
        raise ValueError(
            f"Jumlah observasi harus 70, "
            f"tetapi diterima {len(df)}."
        )

    # Pastikan tidak ada kombinasi kecamatan-partai duplikat
    duplicate_count = df.duplicated(
        subset=[
            "id_kecamatan",
            "id_partai"
        ]
    ).sum()

    if duplicate_count > 0:
        raise ValueError(
            "Ditemukan "
            f"{duplicate_count} observasi "
            "kecamatan-partai yang duplikat."
        )

    # Pastikan ID numerik
    df["id_kecamatan"] = pd.to_numeric(
        df["id_kecamatan"],
        errors="coerce"
    )

    df["id_partai"] = pd.to_numeric(
        df["id_partai"],
        errors="coerce"
    )

    # Pastikan fitur numerik
    df["share_2019"] = pd.to_numeric(
        df["share_2019"],
        errors="coerce"
    )

    df["share_2024"] = pd.to_numeric(
        df["share_2024"],
        errors="coerce"
    )

    # Tidak boleh ada NaN
    if df[
        [
            "id_kecamatan",
            "id_partai",
            "share_2019",
            "share_2024"
        ]
    ].isnull().any().any():

        raise ValueError(
            "Terdapat nilai kosong atau tidak valid "
            "pada dataset."
        )

    # Share tidak boleh negatif
    if (
        (df["share_2019"] < 0).any()
        or
        (df["share_2024"] < 0).any()
    ):
        raise ValueError(
            "Nilai share tidak boleh negatif."
        )

    return df


# ============================================================
# KATEGORI CLUSTER
# ============================================================

def tentukan_kategori_cluster(
    df,
    labels
):
    """
    Menentukan kategori berdasarkan rata-rata
    share 2019 dan share 2024 pada setiap cluster.

    Karena K=2, kategori yang digunakan:

    - Tinggi
    - Rendah

    Nomor cluster dari K-Means tidak dianggap memiliki
    makna substantif. Cluster dipetakan berdasarkan
    mean level share.
    """

    df_temp = df.copy()

    df_temp["cluster_asli"] = labels

    profil = (
        df_temp
        .groupby("cluster_asli")
        .agg(
            mean_share_2019=(
                "share_2019",
                "mean"
            ),
            mean_share_2024=(
                "share_2024",
                "mean"
            )
        )
        .reset_index()
    )

    profil["mean_level"] = (
        profil["mean_share_2019"]
        +
        profil["mean_share_2024"]
    ) / 2

    profil = profil.sort_values(
        "mean_level",
        ascending=False
    ).reset_index(drop=True)

    mapping_kategori = {}

    for posisi, row in profil.iterrows():

        cluster_asli = int(
            row["cluster_asli"]
        )

        if posisi == 0:
            mapping_kategori[
                cluster_asli
            ] = "Tinggi"

        else:
            mapping_kategori[
                cluster_asli
            ] = "Rendah"

    return mapping_kategori, profil


# ============================================================
# JARAK KE CENTROID
# ============================================================

def hitung_jarak_centroid(
    X,
    labels,
    centroids
):
    """
    Menghitung jarak Euclidean setiap observasi
    ke centroid cluster yang ditempatinya.

    Karena hanya menggunakan:
    share_2019
    share_2024

    maka jarak dihitung langsung pada ruang
    dua fitur tersebut.
    """

    jarak = []

    for index, cluster in enumerate(labels):

        titik = X[index]

        centroid = centroids[
            int(cluster)
        ]

        distance = np.linalg.norm(
            titik - centroid
        )

        jarak.append(
            float(distance)
        )

    return jarak


# ============================================================
# PROSES UTAMA
# ============================================================

def process_clustering():

    try:
        # ------------------------------------------------------
        # 1. BACA INPUT DARI LARAVEL
        # ------------------------------------------------------

        input_raw = sys.stdin.read()

        if not input_raw.strip():
            raise ValueError(
                "Input JSON dari Laravel kosong."
            )

        input_data = json.loads(
            input_raw
        )

        if not isinstance(
            input_data,
            list
        ):
            raise ValueError(
                "Format input harus berupa array/list."
            )

        # ------------------------------------------------------
        # 2. BENTUK DATAFRAME
        # ------------------------------------------------------

        df = pd.DataFrame(
            input_data
        )

        # ------------------------------------------------------
        # 3. VALIDASI
        # ------------------------------------------------------

        df = validasi_input(
            df
        )

        # ------------------------------------------------------
        # 4. URUTKAN DATA
        # ------------------------------------------------------
        #
        # Supaya output konsisten dan mudah ditelusuri.
        #

        df = df.sort_values(
            [
                "id_kecamatan",
                "id_partai"
            ]
        ).reset_index(
            drop=True
        )

        # ------------------------------------------------------
        # 5. HITUNG SELISIH SHARE
        # ------------------------------------------------------
        #
        # Trend bukan fitur K-Means.
        #
        # Selisih hanya digunakan sebagai informasi hasil:
        #
        # share_2024 - share_2019
        #

        df["selisih_share"] = (
            df["share_2024"]
            -
            df["share_2019"]
        )

        # ------------------------------------------------------
        # 6. SIAPKAN FITUR K-MEANS
        # ------------------------------------------------------
        #
        # MODEL FINAL:
        #
        # X1 = share_2019
        # X2 = share_2024
        #
        # TANPA SCALING
        #

        fitur = [
            "share_2019",
            "share_2024"
        ]

        X = df[
            fitur
        ].to_numpy(
            dtype=float
        )

        # ------------------------------------------------------
        # 7. VALIDASI JUMLAH DATA
        # ------------------------------------------------------

        jumlah_data = len(X)

        if (
            jumlah_data
            < JUMLAH_OBSERVASI_MINIMAL
        ):
            raise ValueError(
                "Jumlah observasi tidak cukup "
                "untuk menjalankan K-Means."
            )

        if JUMLAH_CLUSTER >= jumlah_data:
            raise ValueError(
                "Jumlah cluster harus lebih kecil "
                "dari jumlah observasi."
            )

        # ------------------------------------------------------
        # 8. JALANKAN K-MEANS
        # ------------------------------------------------------

        kmeans = KMeans(
            n_clusters=JUMLAH_CLUSTER,
            random_state=RANDOM_STATE,
            n_init=N_INIT
        )

        labels = kmeans.fit_predict(
            X
        )

        # ------------------------------------------------------
        # 9. SILHOUETTE SCORE
        # ------------------------------------------------------

        if len(
            np.unique(labels)
        ) > 1:

            silhouette = silhouette_score(
                X,
                labels
            )

        else:

            silhouette = 0.0

        # ------------------------------------------------------
        # 10. TENTUKAN KATEGORI
        # ------------------------------------------------------

        (
            mapping_kategori,
            profil_cluster
        ) = tentukan_kategori_cluster(
            df,
            labels
        )

        # ------------------------------------------------------
        # 11. REMAP NOMOR CLUSTER
        # ------------------------------------------------------
        #
        # Cluster dengan mean share tertinggi
        # menjadi Cluster 1.
        #
        # Cluster dengan mean share lebih rendah
        # menjadi Cluster 2.
        #
        # Ini hanya untuk pelabelan hasil agar konsisten.
        #

        cluster_urut = (
            profil_cluster[
                "cluster_asli"
            ]
            .tolist()
        )

        mapping_cluster = {
            int(cluster_lama): posisi + 1
            for posisi, cluster_lama
            in enumerate(
                cluster_urut
            )
        }

        # ------------------------------------------------------
        # 12. JARAK KE CENTROID
        # ------------------------------------------------------

        jarak_centroid = (
            hitung_jarak_centroid(
                X,
                labels,
                kmeans.cluster_centers_
            )
        )

        # ------------------------------------------------------
        # 13. BENTUK HASIL
        # ------------------------------------------------------

        results = []

        for index, row in df.iterrows():

            cluster_asli = int(
                labels[index]
            )

            cluster_baru = (
                mapping_cluster[
                    cluster_asli
                ]
            )

            kategori = (
                mapping_kategori[
                    cluster_asli
                ]
            )

            share_2019 = float(
                row["share_2019"]
            )

            share_2024 = float(
                row["share_2024"]
            )

            selisih_share = float(
                row["selisih_share"]
            )

            results.append(
                {
                    "id_kecamatan":
                        int(
                            row[
                                "id_kecamatan"
                            ]
                        ),

                    "id_partai":
                        int(
                            row[
                                "id_partai"
                            ]
                        ),

                    "share_2019":
                        round(
                            share_2019,
                            4
                        ),

                    "share_2024":
                        round(
                            share_2024,
                            4
                        ),

                    "selisih_share":
                        round(
                            selisih_share,
                            4
                        ),

                    "cluster":
                        cluster_baru,

                    "kategori_cluster":
                        kategori,

                    "jarak_centroid":
                        round(
                            jarak_centroid[index],
                            6
                        )
                }
            )

        # ------------------------------------------------------
        # 14. OUTPUT JSON
        # ------------------------------------------------------

        output = {
            "success": True,

            "jumlah_data":
                jumlah_data,

            "jumlah_cluster":
                JUMLAH_CLUSTER,

            "metode_scaling":
                "Tanpa Scaling",

            "fitur": [
                "share_2019",
                "share_2024"
            ],

            "silhouette_score":
                round(
                    float(silhouette),
                    4
                ),

            "results":
                results
        }

        print(
            json.dumps(
                output,
                ensure_ascii=False
            )
        )

    except Exception as e:

        kirim_error(
            str(e)
        )

        sys.exit(1)


# ============================================================
# ENTRY POINT
# ============================================================

if __name__ == "__main__":
    process_clustering()