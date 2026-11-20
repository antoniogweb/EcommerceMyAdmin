create table regusers_sso (
	id_user_sso INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_user INT UNSIGNED NOT NULL default 0,
	token_hash CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_sso_codes_code_hash (token_hash),
    KEY idx_sso_codes_expires_at (expires_at),
    KEY idx_sso_codes_id_user (id_user)
);