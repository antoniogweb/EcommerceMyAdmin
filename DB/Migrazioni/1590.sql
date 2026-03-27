ALTER TABLE embeddings
    ADD COLUMN embeddings_title_bin BLOB NULL AFTER embeddings_title,
    ADD COLUMN embeddings_body_bin BLOB NULL AFTER embeddings_body;