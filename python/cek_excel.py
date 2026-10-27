import pandas as pd

file_excel = "Master Data.xlsx"

df = pd.read_excel(
    file_excel,
    sheet_name="master_suara_partai_2024"
)

print("Jumlah baris Excel:", len(df))

print("\nData TPS 1 + Partai 1:")
print(
    df[
        (df["id_tps"] == 1) &
        (df["id_partai"] == 1)
    ].to_string(index=False)
)