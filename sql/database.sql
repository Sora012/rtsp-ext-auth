SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `stream_user` varchar(255) NOT NULL,
  `stream_pass` varchar(255) NOT NULL,
  `stream_path` varchar(255) NOT NULL,
  `viewers` JSON
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`stream_user`,`stream_path`);

ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
