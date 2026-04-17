ALTER TABLE ai_richieste_response
    ADD INDEX idx_airr_tipo_time_console (tipo, time_creazione, console),
    ADD INDEX idx_airr_tipo_ip_time_console (tipo, ip, time_creazione, console),
    ADD INDEX idx_airr_time_console (time_creazione, console);