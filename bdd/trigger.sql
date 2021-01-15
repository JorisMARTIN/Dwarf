------------------
-- PAGE TRIGGER -- 
------------------

-- Trigger for delete a page --

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

-- Trigger for restore a page --

CREATE OR REPLACE function f_removeDeleteForPage() RETURNS trigger AS $$
BEGIN

    if EXISTS (SELECT pageId FROM "DeletePage" WHERE pageId = old.pageId) then
        UPDATE "Page" SET deleted = false WHERE pageId = old.pageId;
        RAISE NOTICE '(t_removeDeleteForPage) Page n° % set deleted to false', old.pageId;
    else 
        RAISE EXCEPTION '(t_removeDeleteForPage) page n° % is not deleted', old.pageId;
    end if;

    RAISE NOTICE '(t_removeDeleteForPage) % in %', TG_OP, TG_TABLE_NAME;
    RETURN old;


END; $$ LANGUAGE 'plpgsql';


CREATE TRIGGER t_removeDeleteForPage
BEFORE
DELETE
ON "DeletePage"
FOR EACH ROW
EXECUTE procedure f_removeDeleteForPage();

-- Trigger for erase a page --

CREATE OR REPLACE function f_erasePage() RETURNS trigger AS $$
BEGIN

    -- Remove page form DeletePage
    DELETE FROM "DeletePage" WHERE pageId = old.pageId;

    -- Remove all rates if exists
    if EXISTS (SELECT pageId FROM "Rate" WHERE pageId = old.pageId) then 
        DELETE FROM "Rate" WHERE pageId = old.pageId;
        RAISE NOTICE '(t_erasePage) Votes associated to page n° % -> Deleted', old.pageId;
    end if;

    -- Remove Frames associated to the Page
    DELETE FROM "Frame" WHERE pageId = old.pageId;
    RAISE NOTICE '(t_erasePage) Frames associated to page n° % -> Deleted', old.pageId;

END; $$ LANGUAGE 'plpgsql';


CREATE TRIGGER t_erasePage
BEFORE
DELETE
ON "Page"
FOR EACH ROW
EXECUTE procedure f_erasePage();