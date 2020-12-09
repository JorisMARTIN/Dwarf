CREATE OR REPLACE function f_setDeleteForPage() RETURNS trigger AS $$
DECLARE

    isDeleted boolean := (SELECT deleted FROM "Page" WHERE pageId = new.pageId);

BEGIN

    if(TG_OP = 'INSERT') then
        if (isDeleted) then
            RAISE EXCEPTION '(t_setDeleteForPage) page n° % is already deleted', new.pageId;
        else 
            UPDATE "Page" SET deleted = true WHERE pageId = new.pageId;
            RAISE NOTICE '(t_setDeleteForPage) Page n° % set deleted to true', new.pageId;
        end if;
    else
        if(EXISTS (SELECT pageId FROM "DeletePage" WHERE pageId = new.pageId)) then
            UPDATE "Page" SET deleted = true WHERE pageId = new.pageId;
            RAISE NOTICE '(t_setDeleteForPage) Page n° % set deleted to false', new.pageId;

            DELETE FROM "DeletePage" WHERE pageId = new.pageId;
            RAISE NOTICE '(t_setDeleteForPage) Page n° % deleted from %', TG_OP, TG_TABLE_NAME;
        else
            RAISE EXCEPTION '(t_setDeleteForPage) Page n° % is not deleted.', new.pageId;
        end if;
    end if;
    RAISE NOTICE '(t_setDeleteForPage) % in %', TG_OP, TG_TABLE_NAME;
    RETURN new;


END; $$ LANGUAGE 'plpgsql';

CREATE TRIGGER t_setDeleteForPage
BEFORE
INSERT or DELETE
ON "DeletePage"
FOR EACH ROW
EXECUTE procedure f_setDeleteForPage();
