ALTER TABLE `tbl_users` DROP INDEX `tbl_role`, ADD UNIQUE `tbl_role` (`role`) USING BTREE;
ALTER TABLE `tbl_favorite` ADD UNIQUE `tbl_users` (`user_id`);
ALTER TABLE `tbl_favorite` ADD UNIQUE `tbl_audio` (`audio_id`);
ALTER TABLE `tbl_audio` ADD UNIQUE `tbl_album` (`album_id`);
ALTER TABLE `tbl_audio` ADD UNIQUE `tbl_artist` (`artist_id`);
ALTER TABLE `tbl_playlist` ADD UNIQUE `tbl_users` (`user_id`);
ALTER TABLE `tbl_playlist` ADD UNIQUE `tbl_category` (`type`);
ALTER TABLE `tbl_category_audio` ADD UNIQUE `tbl_category` (`category_id`);
ALTER TABLE `tbl_category_audio` ADD UNIQUE `tbl_audio` (`audio_id`);







25/05
ALTER TABLE `tbl_album` CHANGE `image` `image` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `tbl_audio` CHANGE `imageUrl` `imageUrl` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
ALTER TABLE `tbl_playlist` CHANGE `image` `image` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;
