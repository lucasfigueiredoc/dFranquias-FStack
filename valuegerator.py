import random
from datetime import datetime, timedelta

# Função para gerar uma data de nascimento aleatória
def random_birthdate():
    start_date = datetime(2010, 1, 1)
    end_date = datetime.now()
    random_date = start_date + timedelta(days=random.randint(0, (end_date - start_date).days))
    return random_date.strftime('%Y-%m-%d')

# Geração de uma query MySQL com 1000 valores sem IDs repetidos
query = "INSERT INTO `gado` (`id`, `codigo`, `leite`, `racao`, `peso`, `situacao`, `nascimento`) VALUES\n"

used_ids = set()
used_codigos = set()

for _ in range(1000):
    while True:
        id_value = random.randint(1, 1001)
        if id_value not in used_ids:
            used_ids.add(id_value)
            break

    while True:
        codigo_value = random.randint(1, 1001)
        if codigo_value not in used_codigos:
            used_codigos.add(codigo_value)
            break

    leite_value = round(random.uniform(0, 186), 2)
    racao_value = random.randint(0, 500)
    peso_value = random.randint(160, 1410)
    situacao_value = random.randint(0, 2)
    nascimento_value = random_birthdate()

    query += f"({id_value}, {codigo_value}, {leite_value}, {racao_value}, {peso_value}, {situacao_value}, '{nascimento_value}'),\n"

# Remover a vírgula extra no final
query = query.rstrip(",\n")


file = open("arquivo.txt", "w")
file.write(query)
file.close()
