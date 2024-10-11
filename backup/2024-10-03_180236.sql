DROP TABLE tbl_instansi;

CREATE TABLE `tbl_instansi` (
  `instansi` int(11) NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`instansi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE tbl_surat_keluar;

CREATE TABLE `tbl_surat_keluar` (
  `id_surat_keluar` int(11) NOT NULL AUTO_INCREMENT,
  `no_surat` varchar(100) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `pengirim` varchar(100) NOT NULL,
  `tujuan` varchar(100) NOT NULL,
  PRIMARY KEY (`id_surat_keluar`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tbl_surat_keluar VALUES("1","213sfds","2024-10-03","asdsaa","BPKP");



DROP TABLE tbl_surat_masuk;

CREATE TABLE `tbl_surat_masuk` (
  `id_surat_masuk` int(11) NOT NULL AUTO_INCREMENT,
  `no_surat` varchar(100) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `pengirim` varchar(100) NOT NULL,
  `tujuan` varchar(100) NOT NULL,
  PRIMARY KEY (`id_surat_masuk`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tbl_surat_masuk VALUES("1","2204123","2024-10-03","BPKP JABAR","BPKP PUSAT");
INSERT INTO tbl_surat_masuk VALUES("2","kh-2123","2024-10-01","BPKP","RAFI
");



DROP TABLE tbl_user;

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tbl_user VALUES("1","admin","admin123","alrafirizky","220414023","1");
INSERT INTO tbl_user VALUES("2","admin","482c811da5d5b4bc6d497ffa98491e38","Admin Name","12345678","1");
INSERT INTO tbl_user VALUES("3","admin","e9ddba245ea54af4aa75f69aa436c2fc","Alrafirizky","220414023","1");
INSERT INTO tbl_user VALUES("4","admin","9656f3d0ca4a06491ec6c32118df2b7e","Alrafirizky","220414023","1");



