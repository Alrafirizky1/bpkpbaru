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
  `file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_surat_keluar`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tbl_surat_keluar VALUES("20","PE.12.02/S1592/2.1/2024","2024-08-26","BPKP Jawa Barat","INSPEKTUR JENDRAL KEMENTRIAN PUPR","8977-TUGAS MAGANG.xlsx");



DROP TABLE tbl_surat_masuk;

CREATE TABLE `tbl_surat_masuk` (
  `id_surat_masuk` int(11) NOT NULL AUTO_INCREMENT,
  `no_surat` varchar(100) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `pengirim` varchar(100) NOT NULL,
  `tujuan` varchar(100) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_surat_masuk`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




DROP TABLE tbl_user;

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO tbl_user VALUES("3","rafi01","Rafi1933","Alrafir","1","1");
INSERT INTO tbl_user VALUES("4","admin","9656f3d0ca4a06491ec6c32118df2b7e","Alrafirizkyyy","220414023","1");
INSERT INTO tbl_user VALUES("6","alrafirizky13","ed938017f5a38079e3faef48f0b09c51","rafi","220414023","2");
INSERT INTO tbl_user VALUES("7","userr","6ad14ba9986e3615423dfca256d04e3f","user","123","3");
INSERT INTO tbl_user VALUES("10","rafi01","Rafi1933","Alrafir","1","1");



