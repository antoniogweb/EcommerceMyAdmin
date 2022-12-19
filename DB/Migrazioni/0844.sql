update fatture set data_fattura = date_format(data_creazione, "%Y-%m-%d") where 1;
