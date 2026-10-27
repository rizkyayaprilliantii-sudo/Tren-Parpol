import pandas as pd

file_excel = "Data PKL (Parpol).xlsx"

print("Membaca Excel...")

df_tps = pd.read_excel(
    file_excel,
    sheet_name="TPS"
)

df_suara = pd.read_excel(
    file_excel,
    sheet_name="Suara Partai 2024"
)

print()
print("Kolom sheet TPS:")
print(df_tps.columns.tolist())

print()
print("Kolom sheet Suara Partai 2024:")
print(df_suara.columns.tolist())


def bersihkan(teks):
    if pd.isna(teks):
        return ""
    return " ".join(str(teks).replace("\u00a0", " ").split()).strip()


for kolom in ["Kecamatan", "Kelurahan", "TPS"]:
    if kolom in df_tps.columns:
        df_tps[kolom] = df_tps[kolom].apply(bersihkan)

    if kolom in df_suara.columns:
        df_suara[kolom] = df_suara[kolom].apply(bersihkan)


kolom_wajib_tps = ["Kecamatan", "Kelurahan", "TPS"]
kolom_wajib_suara = ["Kecamatan", "Kelurahan", "TPS"]

for kolom in kolom_wajib_tps:
    if kolom not in df_tps.columns:
        print()
        print(f"ERROR: Kolom '{kolom}' tidak ditemukan di sheet TPS.")
        raise SystemExit

for kolom in kolom_wajib_suara:
    if kolom not in df_suara.columns:
        print()
        print(f"ERROR: Kolom '{kolom}' tidak ditemukan di sheet Suara Partai 2024.")
        raise SystemExit


# Buat kunci gabungan Kecamatan + Kelurahan + TPS
df_tps["kunci"] = (
    df_tps["Kecamatan"] + " | " +
    df_tps["Kelurahan"] + " | " +
    df_tps["TPS"]
)

df_suara["kunci"] = (
    df_suara["Kecamatan"] + " | " +
    df_suara["Kelurahan"] + " | " +
    df_suara["TPS"]
)


kunci_tps = set(df_tps["kunci"])
kunci_suara = set(df_suara["kunci"])


# TPS yang ada di Suara Partai 2024 tetapi tidak ada di sheet TPS
tps_tidak_ditemukan = sorted(
    kunci_suara - kunci_tps
)


print()
print("=" * 70)
print("HASIL PEMERIKSAAN TPS")
print("=" * 70)

print()
print(f"Jumlah TPS di sheet TPS: {len(kunci_tps)}")
print(f"Jumlah kombinasi TPS di Suara Partai 2024: {len(kunci_suara)}")
print(f"Jumlah TPS yang tidak ditemukan: {len(tps_tidak_ditemukan)}")

if tps_tidak_ditemukan:
    print()
    print("TPS YANG TIDAK DITEMUKAN:")
    print()

    for nomor, kunci in enumerate(tps_tidak_ditemukan, start=1):
        print(f"{nomor}. {kunci}")
else:
    print()
    print("Semua TPS pada Suara Partai 2024 ditemukan di sheet TPS.")


# Cek juga TPS yang ada di sheet TPS tetapi tidak digunakan di Suara Partai 2024
tps_tidak_digunakan = sorted(
    kunci_tps - kunci_suara
)

print()
print("=" * 70)
print("TPS DI SHEET TPS YANG TIDAK ADA DI SUARA PARTAI 2024")
print("=" * 70)

print()
print(f"Jumlah: {len(tps_tidak_digunakan)}")

if tps_tidak_digunakan:
    for nomor, kunci in enumerate(tps_tidak_digunakan, start=1):
        print(f"{nomor}. {kunci}")
else:
    print("Semua TPS pada sheet TPS digunakan di Suara Partai 2024.")