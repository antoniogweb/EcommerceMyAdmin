ALTER TABLE ai_richieste_response
    DROP INDEX idx_airr_tipo_time,
    DROP INDEX idx_airr_tipo_ip_time,
    DROP INDEX idx_airr_time;