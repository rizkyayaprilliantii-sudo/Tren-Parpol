import pandas as pd

file_excel = "Data PKL (Parpol).xlsx"

df_kelurahan = pd.read_excel(
    file_excel,
    sheet_name="Kelurahan"
)

df_tps = pd.read_excel(
    file_excel,
    sheet_name="TPS"
)

def cek_teks(teks):
    teks = str(teks)

    print("Teks:", repr(teks))
    print("Panjang:", len(teks))
    print("Karakter:")
    
    for i, karakter in enumerate(teks):
        print(
            f"  posisi {i}: "
            f"{repr(karakter)} "
            f"Unicode U+{ord(karakter):04X}"
        )

print("=== KELAYAN DALAM DI SHEET KELURAHAN ===")

data_kelurahan = df_kelurahan[
    df_kelurahan["Kelurahan"].astype(str).str.contains(
        "Kelayan",
        case=False,
        na=False
    )
]

for _, row in data_kelurahan.iterrows():
    print()
    print("Kecamatan:", repr(row["Kecamatan"]))
    cek_teks(row["Kelurahan"])

print()
print("=== KELAYAN DALAM DI SHEET TPS ===")

data_tps = df_tps[
    df_tps["Kelurahan"].astype(str).str.contains(
        "Kelayan",
        case=False,
        na=False
    )
]

for _, row in data_tps.head(5).iterrows():
    print()
    print("Kecamatan:", repr(row["Kecamatan"]))
    print("TPS:", repr(row["TPS"]))
    cek_teks(row["Kelurahan"])