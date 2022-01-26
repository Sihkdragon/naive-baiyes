# from lib.test import *
import pandas
import matplotlib.pyplot as plt
import seaborn as sns
sns.set_style("darkgrid")
# Baca Data

csvpath = 'd:/Code/PYTHON/AI/Program/data.csv'
data = pandas.read_csv(csvpath)
print(data)
data.head(10)
# Input Data Uji

# Hitung Probabilitas Setiap Kelas
