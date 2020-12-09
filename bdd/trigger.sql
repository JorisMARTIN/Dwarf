CREATE OR REPLACE function f_setDeleteForPage() RETURNS trigger AS $$
DECLARE

    isDeleted boolean := (SELECT deleted FROM "Page" WHERE pageId = new.pageId);

BEGIN

    if (isDeleted) then
        RAISE EXCEPTION '(t_setDeleteForPage) page n° % is already deleted', new.pageId;
    else 
        UPDATE "Page" SET deleted = true WHERE pageId = new.pageId;
        RAISE NOTICE '(t_setDeleteForPage) Page n° % set deleted to true', new.pageId;
    end if;

	RAISE NOTICE '(t_setDeleteForPage) % in %', TG_OP, TG_TABLE_NAME;
	RETURN new;

END; $$ LANGUAGE 'plpgsql';

CREATE TRIGGER t_setDeleteForPage
BEFORE
INSERT
ON "DeletePage"
FOR EACH ROW
EXECUTE procedure f_setDeleteForPage();
