ALTER TABLE regusers_sso ADD UNIQUE KEY uq_regusers_sso_state_hash (state_hash);
