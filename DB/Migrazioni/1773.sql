ALTER TABLE regusers_sso ADD state_hash CHAR(64) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL AFTER token_hash;
