ALTER TABLE regusers_sso ADD token_used TINYINT(1) NOT NULL DEFAULT 0 AFTER used_at;
