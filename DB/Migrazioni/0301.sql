INSERT INTO `pagamenti` (`id_pagamento`, `data_creazione`, `titolo`, `codice`, `attivo`, `id_order`) VALUES (NULL, CURRENT_TIMESTAMP, 'Pagamento online tramite PayPal', 'paypal', '1', '1'), (NULL, CURRENT_TIMESTAMP, 'Pagamento online tramite carta di credito', 'carta_di_credito', '0', '2'), (NULL, CURRENT_TIMESTAMP, 'Bonifico bancario', 'bonifico', '1', '3'), (NULL, CURRENT_TIMESTAMP, 'Contrassegno (pagamento alla consegna)', 'contrassegno', '0', '4'), (NULL, CURRENT_TIMESTAMP, 'Bollettino postale', 'bollettino', '0', '5')
