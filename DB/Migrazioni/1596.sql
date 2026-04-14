ALTER TABLE ai_richieste_response
    ADD INDEX idx_airr_tipo_time (tipo, time_creazione),
    ADD INDEX idx_airr_tipo_ip_time (tipo, ip, time_creazione),
    ADD INDEX idx_airr_time (time_creazione);