INSERT INTO `eventi_retargeting_gruppi` (`titolo`, `model`, `attivo`, `id_order`, `condizioni`, `clausola_where`, `blocca_reinvio_mail_stesso`) VALUES ('Manda email quando i CREDITI sono in scadenza', 'CreditiModel', '1', '10', 'attiva_crediti=1', 'in_scadenza = 1 AND attivo = 1', 'EVENTO_ELEMENTO');
