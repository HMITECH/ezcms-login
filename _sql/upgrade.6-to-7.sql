-- ezCMS upgrade: 6.x  ->  7.0
-- http://www.hmi-tech.net
--
-- Schema is unchanged between 6.x and 7 (no columns added or dropped) — this
-- migration only brings the indexes in line with 7.0: it adds the indexes the
-- per-user activity log and revision lookups rely on, and drops the redundant
-- duplicate indexes that shipped in 6.x.
--
-- The `IF NOT EXISTS` / `IF EXISTS` clauses make this safe to run more than
-- once. They require MariaDB (10.0+). On MySQL, remove those clauses and run
-- each statement once.

-- git_files: cover per-user activity log (WHERE createdby) + time ordering
ALTER TABLE `git_files` ADD KEY IF NOT EXISTS `createdby` (`createdby`);
ALTER TABLE `git_files` ADD KEY IF NOT EXISTS `createdon` (`createdon`);

-- git_pages: already has createdby + page_id; add time ordering
ALTER TABLE `git_pages` ADD KEY IF NOT EXISTS `createdon` (`createdon`);

-- site: cover per-user activity log (WHERE createdby)
ALTER TABLE `site` ADD KEY IF NOT EXISTS `createdby` (`createdby`);

-- pages: drop the redundant duplicates from 6.x
--   `proprity` is a typo'd duplicate of `priority`
--   `url_2`    duplicates the UNIQUE KEY `url`
ALTER TABLE `pages` DROP INDEX IF EXISTS `proprity`;
ALTER TABLE `pages` DROP INDEX IF EXISTS `url_2`;
