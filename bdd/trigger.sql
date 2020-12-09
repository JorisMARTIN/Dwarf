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

CREATE OR REPLACE function f_removeDeleteForPage() RETURNS trigger AS $$
DECLARE

    isInTable boolean := EXISTS (SELECT deleted FROM "DeletePage" WHERE pageId = old.pageId);

BEGIN

    if (isInTable) then
        UPDATE "Page" SET deleted = false WHERE pageId = old.pageId;
        RAISE NOTICE '(t_removeDeleteForPage) Page n° % set deleted to false', old.pageId;
    else 
        RAISE EXCEPTION '(t_removeDeleteForPage) page n° % is already deleted', new.pageId;
    end if;

    RAISE NOTICE '(t_removeDeleteForPage) % in %', TG_OP, TG_TABLE_NAME;
    RETURN new;


END; $$ LANGUAGE 'plpgsql';


CREATE TRIGGER t_removeDeleteForPage
BEFORE
DELETE
ON "DeletePage"
FOR EACH ROW
EXECUTE procedure f_removeDeleteForPage();