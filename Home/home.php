<?php
session_start();
if (!isset($_SESSION['sudahlogin']) || $_SESSION['sudahlogin'] != true) {
  header("Location: ../Signup/signup.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background-color: #f7fafc;
      color: #2d3748;
      padding-bottom: 60px;
    }

    .navbar {
      background-color: #2BB6C4;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 1000;
      padding: 16px 45px;
      border-bottom-left-radius: 28px;
      border-bottom-right-radius: 28px;
      box-shadow: 0 8px 20px rgb(200 199 199);
    }

    .logo-area {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .logo-img {
      height: 32px;
      width: auto;
    }

    .nav-links {
      display: flex;
      gap: 35px;
    }
    .nav-links a {
      color: rgba(255, 255, 255, 0.85);
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.2s;
    }
    .nav-links a.active, .nav-links a:hover {
      color: #ffffff;
      font-weight: 600;
    }

    .nav-actions {
      display: flex;
      align-items: center;
      gap: 22px;
    }

    .nav-icon-btn {
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: transform 0.2s;
    }
    .nav-icon-btn:hover {
      transform: scale(1.1);
    }

    .container {
      max-width: 1280px;
      margin: 30px auto;
      padding: 0 24px;
    }

    .greeting-block {
      margin-bottom: 30px;
    }
    .greeting-banner {
      height: 52px;
      display: block;
      margin-bottom: 2px;
    }

    .stats-action-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 24px;
      margin-bottom: 45px;
    }
    .dashboard-card {
      background: #ffffff;
      border-radius: 24px;
      padding: 24px;
      display: flex;
      align-items: center;
      gap: 16px;
      box-shadow: 0 12px 26px rgb(163 157 157 / 0.7);
      border: 1px solid rgba(226, 232, 240, 0.7);
      position: relative;
    }
    .card-icon {
      width: 52px;
      height: 52px;
      object-fit: contain;
    }
    .card-content .label {
      font-size: 12px;
      color: #a0aec0;
      font-weight: 500;
    }
    .card-content .main-value {
      font-size: 22px;
      font-weight: 700;
      color: #1a202c;
      margin: 1px 0;
      line-height: 1.2;
    }
    .card-content .helper-text {
      font-size: 10px;
      color: #a0aec0;
    }
    .stats-action-grid .dashboard-card:nth-child(2)::after {
      content: "";
      position: absolute;
      right: -13px;
      top: 25%;
      height: 50%;
      width: 1px;
      background-color: #e2e8f0;
    }

    .clickable-card {
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .clickable-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 16px 32px rgb(106 106 106 / 0.7);
    }
    .clickable-card .main-value {
      color: #000000 !important;
    }
    .clickable-card .helper-text {
      color: #2BB6C4 !important;
      font-weight: 500;
      font-size: 11px;
    }

    .section-heading {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 20px;
      color: #1a202c;
    }

    .mitra-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 24px;
      margin-bottom: 45px;
    }
    .mitra-grid .item-media {
      padding: 0; height: 165px;
    }
    .mitra-grid .item-media img {
      width: 100%;
      height: 100%;
      max-width: none;
      max-height: none;
      object-fit: cover;
      border-radius: 0;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 18px;
    }
    .item-box {
      background: #ffffff;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 12px 26px rgb(175 171 171 / 0.8);
      border: 1px solid rgba(226, 232, 240, 0.7);
      display: flex;
      flex-direction: column;
    }
    .item-media {
      width: 100%;
      height: 135px;
      background-color: #ffffff;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 12px;
    }
    .item-media img {
      max-width: 100%;
      max-height: 100%;
      object-fit: fill;
      border-radius: 18px;
    }
    .item-body {
      padding: 16px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }
    .item-title {
      font-size: 14px;
      font-weight: 700;
      color: #1a202c;
    }
    .item-description {
      font-size: 10px;
      color: #718096;
      margin-top: 3px;
      line-height: 1.4;
      flex-grow: 1;
    }

    .item-action-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-top: 12px;
    }
    .btn-cyan {
      background-color: #2BB6C4;
      color: white;
      border: none;
      padding: 6px 18px;
      font-size: 11px;
      font-weight: 600;
      border-radius: 20px;
      cursor: pointer;
      transition: background 0.2s;
    }
    .btn-cyan:hover {
      background-color: #1fa4b2;
    }
    .mitra-box-layout {
      display: flex;
      justify-content: space-between;
      align-items: flex-start; margin-top: 6px; gap: 10px;
    }
    .mitra-box-layout .item-description {
      margin-top: 0;
    }

    #detailView { display: none; }
    .detail-top-bar {
      background-color: #2BB6C4;
      padding: 25px 40px;
      border-bottom-left-radius: 35px;
      border-bottom-right-radius: 35px;
      margin-bottom: 45px;
    }
    .detail-header-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 14px 24px;
      display: flex;
      align-items: center;
      gap: 16px;
      margin: 0 auto;
    }
    .detail-back-btn {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background-color: #e6f7f8;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: #2BB6C4;
      transition: background 0.2s;
    }
    .detail-back-btn:hover {
      background-color: #d1f0f2;
    }
    .detail-header-text h2 {
      color: #2BB6C4;
      font-size: 20px;
      font-weight: 700;
      line-height: 1.2;
    }
    .detail-header-text p {
      color: #718096;
      font-size: 13px;
      font-weight: 400;
      margin-top: 2px;
    }

    .detail-content-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 30px;
    }
    .detail-card-left {
      background: #ffffff;
      border: 1px solid #E2E8F0;
      border-radius: 25px;
      padding: 10px;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 480px;
      overflow: hidden;
    }
    .detail-card-left img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
    .detail-card-right {
      background: #ffffff;
      border: 1px solid #E2E8F0;
      border-radius: 20px;
      padding: 40px;
      display: flex;
      flex-direction: column;
    }
    .detail-card-right h1 {
      font-size: 28px;
      font-weight: 700;
      color: #2BB6C4;
      margin-bottom: 24px;
      line-height: 1.2;
    }
    .detail-card-right h3 {
      font-size: 14px;
      font-weight: 700;
      color: #718096;
      margin-bottom: 12px;
    }
    .detail-card-right p.desc {
      font-size: 15px;
      color: #718096;
      line-height: 1.6;
      margin-bottom: 35px;
      font-weight: 400;
    }
    .detail-loc-box {
      border: 1px solid #E2E8F0;
      border-radius: 16px;
      padding: 18px 20px;
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 35px;
      background-color: #ffffff;
    }
    .detail-loc-icon {
      width: 48px;
      height: 48px;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-shrink: 0;
    }
    .detail-loc-text h4 {
      font-size: 18px;
      font-weight: 700;
      color: #000;
      margin-bottom: 4px;
    }
    .detail-loc-text p {
      font-size: 11px;
      color: #a0aec0;
      margin-bottom: 6px;
    }
    .detail-loc-text a {
      font-size: 12px;
      color: #2BB6C4;
      font-weight: 600;
      text-decoration: none;
    }
    .detail-btn-group {
      display: flex;
      gap: 16px;
      margin-top: auto;
    }
    .detail-btn-group button {
      flex: 1;
      padding: 16px 0;
      border-radius: 30px;
      font-size: 16px;
      font-weight: 700;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s;
    }
    .btn-keranjang-detail {
      background-color: #ffffff;
      color: #2BB6C4;
      border: 2px solid #2BB6C4;
    }
    .btn-tukar-detail {
      background-color: #2BB6C4;
      color: #ffffff;
      border: none;
    }

    #cartView {
      display: none;
    }

    .page-header-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 16px 24px;
      display: flex;
      align-items: center;
      gap: 16px;
      border: 1px solid #E2E8F0;
      margin-bottom: 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    }
    .page-back-btn {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background-color: #e6f7f8;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: #2BB6C4; transition: background 0.2s;
    }
    .page-back-btn:hover {
      background-color: #d1f0f2;
    }
    .page-header-text h2 {
      color: #2BB6C4;
      font-size: 22px;
      font-weight: 700;
      line-height: 1.2;
    }
    .page-header-text p {
      color: #718096;
      font-size: 14px;
      margin-top: 2px;
    }

    .empty-cart-container {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 180px 0;
    }
    .empty-cart-container img {
      max-width: 320px;
    }

    .cart-list {
      display: flex;
      flex-direction: column;
    }
    .cart-item {
      display: flex;
      align-items: center;
      padding: 24px 0;
      border-bottom: 1px solid #E2E8F0;
      gap: 24px;
    }
    .cart-item-image {
      width: 110px;
      height: 110px;
      background: #ffffff;
      border: 1px solid #E2E8F0;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.03);
    }
    .cart-item-image img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }
    .cart-item-details {
      flex: 1;
    }
    .cart-item-title {
      font-size: 20px;
      font-weight: 700;
      color: #000;
      margin-bottom: 8px;
    }
    .cart-item-desc {
      font-size: 13px;
      color: #718096;
      max-width: 500px;
      line-height: 1.5;
    }
    .cart-item-quantity {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-right: 20px;
    }
    .qty-btn {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      border: 1px solid #000;
      background: transparent;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 20px;
      color: #000;
      font-weight: 300;
      padding-bottom: 2px;
    }
    .qty-value {
      font-size: 18px;
      font-weight: 600;
      min-width: 20px;
      text-align: center;
      color: #000;
    }

    #historyView {
      display: none;
    }
    .history-list-container {
      padding: 0 10px;
    }
    .history-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 24px 0;
      border-bottom: 1px solid #E2E8F0;
    }
    .history-item:last-child {
      border-bottom: none;
    }
    .history-title {
      font-size: 18px;
      font-weight: 600; color: #000;
      margin-bottom: 6px;
    }
    .history-date {
      font-size: 13px;
      color: #a0aec0;
    }
    .history-amount {
      font-size: 16px;
      font-weight: 600;
      color: #000;
    }

    @media (max-width: 900px) {
      .detail-content-grid {
        grid-template-columns: 1fr;
      }
      .detail-card-left {
        height: 350px;
        padding: 20px;
      }
      .cart-item {
        flex-direction: column;
        align-items: flex-start;
      }
      .cart-item-quantity {
        margin-left: auto;
      }
    }
    @media (max-width: 1100px) {
      .stats-action-grid, .mitra-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      .product-grid {
        grid-template-columns: repeat(3, 1fr);
      }
      .stats-action-grid .dashboard-card:nth-child(2)::after {
        display: none;
      }
    }
    @media (max-width: 650px) {
      .stats-action-grid, .mitra-grid, .product-grid {
        grid-template-columns: 1fr;
      }
      .navbar {
        flex-direction: column;
        gap: 15px;
        padding: 20px;
      }
      .detail-top-bar {
        padding: 20px;
        border-radius: 0 0 20px 20px;
      }
    }

    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      font-family: 'Poppins', sans-serif;
      padding: 20px;
    }

    .modal-content {
      background-color: #ffffff;
      width: 100%;
      max-width: 900px;
      border-radius: 24px;
      position: relative;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      display: flex;
      flex-direction: column;
      overflow: hidden;
      animation: modalMuncul 0.3s ease-out;
    }

    @keyframes modalMuncul {
      from { opacity: 0; transform: translateY(20px) scale(0.95); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .close-btn {
      position: absolute;
      top: 24px;
      right: 24px;
      width: 36px;
      height: 36px;
      background-color: #000000;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: none;
      z-index: 10;
      transition: background 0.2s;
    }

    .close-btn:hover {
      background-color: #333333;
    }

    .close-btn svg {
      width: 16px;
      height: 16px;
      stroke-width: 3px;
    }

    .modal-body {
      padding: 40px 50px;
      padding-bottom: 30px;
    }

    .modal-title {
      font-size: 38px;
      font-weight: 800;
      color: #000000;
      margin-bottom: 24px;
      line-height: 1.2;
    }

    .desc-box {
      border: 1.5px solid #E2E8F0;
      border-radius: 16px;
      padding: 24px 30px;
      font-size: 17px;
      line-height: 1.6;
      color: #1A202C;
      margin-bottom: 30px;
    }

    .sdg-image-wrapper {
      width: 100%;
      display: flex;
      justify-content: center;
    }

    .sdg-img {
      width: 100%;
      max-width: 100%;
      height: auto;
      object-fit: contain;
    }

    .modal-footer {
      background-color: #E2E8F0;
      padding: 16px 50px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-footer-left {
      font-size: 11px;
      color: #4A5568;
      font-weight: 500;
    }

    .modal-footer-right {
      display: flex;
      gap: 20px;
    }

    .modal-footer-right a {
      font-size: 11px;
      color: #4A5568;
      text-decoration: none;
      font-weight: 500;
    }

    .modal-footer-right a:hover {
      color: #000;
    }

    @media (max-width: 768px) {
      .modal-body {
        padding: 30px 20px;
      }
      .modal-footer {
        flex-direction: column;
        gap: 10px;
        padding: 16px 20px;
        text-align: center;
      }
      .desc-box {
        font-size: 14px;
        padding: 16px;
      }
      .modal-title {
        font-size: 28px;
      }
    }
  </style>
</head>
<body>

<header id="mainNavbar" class="navbar">
  <div class="logo-area">
    <img src="../Home/img/Group 126.png" alt="Logo" class="logo-img" style="height: 50px;">
  </div>
  <nav class="nav-links">
    <a href="#" class="active" id="navHome">Home</a>
    <a href="#" id="navCart">Keranjang</a>
    <a href="#" id="navHistory">Riwayat</a>
  </nav>
  <div class="nav-actions">
    <div class="nav-icon-btn">
      <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/></svg>
    </div>
    <a class="nav-icon-btn" id="navProfile" href="../profil/profil.php" aria-label="Profil">
      <svg width="22" height="22" fill="currentColor" viewBox="0 0 16 16"><path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
    </a>
  </div>
</header>

<div id="mainDashboardView">
  <main class="container">
    <section class="greeting-block">
      <img src="../Home/img/Group 146.png" alt="Hallo, Vicent" class="greeting-banner" style="height: 80px">
    </section>

    <!--<iframe allow="autoplay *; encrypted-media *;" frameborder="0" height="200" style="padding-bottom:30px;width:100%;max-width:1246px;overflow:hidden;background:transparent;" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-storage-access-by-user-activation allow-top-navigation-by-user-activation" src="https://embed.music.apple.com/id/album/its-only-me-studio-version/1532400887?i=1532400891"></iframe>-->

    <section class="stats-action-grid">
      <div class="dashboard-card">
        <img src="../Home/img/Token Coins.png" alt="Poin Anda" class="card-icon">
        <div class="card-content">
          <div class="label">Poin Anda</div>
          <div class="main-value">0 Poin</div>
          <div class="helper-text">Tukarkan poin ke voucher, yuk</div>
        </div>
      </div>
      <div class="dashboard-card">
        <img src="../Home/img/Nappy.png" alt="Total Popok" class="card-icon">
        <div class="card-content">
          <div class="label">Total Popok</div>
          <div class="main-value">0 Pcs</div>
          <div class="helper-text">Popok yang sudah kamu setor</div>
        </div>
      </div>
      <div class="dashboard-card clickable-card" id="btnSetorPopok" onclick="window.location.href='#'">
        <img src="../Home/img/Nappy.png" alt="Setor Popok" class="card-icon">
        <div class="card-content">
          <div class="main-value">Setor Popok</div>
          <div class="helper-text">Ubah popok mu</div>
        </div>
      </div>
      <div class="dashboard-card clickable-card" id="btnPenukaran" onclick="window.location.href='#'">
        <img src="../Home/img/mdi_marketplace.png" alt="Penukaran" class="card-icon">
        <div class="card-content">
          <div class="main-value">Penukaran</div>
          <div class="helper-text">Tukar poin mu</div>
        </div>
      </div>
    </section>

    <section>
      <h2 class="section-heading">Lokasi Mitra</h2>
      <div class="mitra-grid">
        <div class="item-box">
          <div class="item-media"><img src="../Home/img/sudimoro.png" alt="Mitra 1"></div>
          <div class="item-body">
            <div class="item-title">Mitra 1 - Sudimoro</div>
            <div class="mitra-box-layout"><div class="item-description">Jl. Ini dulu aja, No. 09, Kec. Mojolangu</div><button class="btn-cyan">Lihat</button></div>
          </div>
        </div>
        <div class="item-box">
          <div class="item-media"><img src="../Home/img/kalpataru.png" alt="Mitra 2"></div>
          <div class="item-body">
            <div class="item-title">Mitra 2 - Kalpataru</div>
            <div class="mitra-box-layout"><div class="item-description">Jl. Ini dulu aja, No. 09, Kec. Cibaduyut</div><button class="btn-cyan">Lihat</button></div>
          </div>
        </div>
        <div class="item-box">
          <div class="item-media"><img src="../Home/img/dinoyo.png" alt="Mitra 3"></div>
          <div class="item-body">
            <div class="item-title">Mitra 3 - Dinoyo</div>
            <div class="mitra-box-layout"><div class="item-description">Jl. Ini dulu aja, No. 09, Kec. Cibaduyut</div><button class="btn-cyan">Lihat</button></div>
          </div>
        </div>
        <div class="item-box">
          <div class="item-media"><img src="../Home/img/sigura.png" alt="Mitra 4"></div>
          <div class="item-body">
            <div class="item-title">Mitra 4 - Sigura</div>
            <div class="mitra-box-layout"><div class="item-description">Jl. Ini dulu aja, No. 09, Kec. Cibaduyut</div><button class="btn-cyan">Lihat</button></div>
          </div>
        </div>
      </div>
    </section>

    <section>
      <h2 class="section-heading">Tukar Poin</h2>
      <div class="product-grid">
        <div class="item-box">
          <div class="item-media"><img src="../Home/img/sabunmandibayi.png" alt="Sabun"></div>
          <div class="item-body">
            <div class="item-title">Sabun Mandi Bayi - My Baby</div>
            <div class="item-description">Sabun cuci piring dengan berat 100ml, bisa digunakan di rumah sudah berbahan food grade</div>
            <div class="item-action-row"><div></div><button class="btn-cyan btn-lihat-produk">Lihat</button></div>
          </div>
        </div>
        <div class="item-box">
          <div class="item-media"><img src="../Home/img/pupuknpk.png" alt="Pupuk"></div>
          <div class="item-body">
            <div class="item-title">Pupuk ZA Plus</div>
            <div class="item-description">Pupuk yang bermanfaat bagi tanaman dengan formula NPK terlengkap.</div>
            <div class="item-action-row"><div></div><button class="btn-cyan btn-lihat-produk">Lihat</button></div>
          </div>
        </div>
        <div class="item-box">
          <div class="item-media"><img src="../Home/img/sabunbatang.png" alt="Sabun Batang"></div>
          <div class="item-body">
            <div class="item-title">Sabun Batang</div>
            <div class="item-description">Sabun batang anti bakteri yang wanginya segar dan melembabkan kulit keluarga.</div>
            <div class="item-action-row"><div></div><button class="btn-cyan btn-lihat-produk">Lihat</button></div>
          </div>
        </div>
        <div class="item-box">
          <div class="item-media"><img src="../Home/img/shampoobayi.png" alt="Shampo"></div>
          <div class="item-body">
            <div class="item-title">Shampo Bayi</div>
            <div class="item-description">Shampo anti pedih di mata, membuat rambut si kecil halus dan mudah disisir.</div>
            <div class="item-action-row"><div></div><button class="btn-cyan btn-lihat-produk">Lihat</button></div>
          </div>
        </div>
        <div class="item-box">
          <div class="item-media"><img src="../Home/img/tisubasah.png" alt="Tisu"></div>
          <div class="item-body">
            <div class="item-title">Tisu Basah</div>
            <div class="item-description">Tisu basah bebas alkohol, sangat higienis untuk membersihkan tangan atau peralatan.</div>
            <div class="item-action-row"><div></div><button class="btn-cyan btn-lihat-produk">Lihat</button></div>
          </div>
        </div>
      </div>
    </section>
  </main>
</div>

<div id="detailView">
  <div class="detail-top-bar">
    <div class="detail-header-card">
      <button id="btnBackDashboard" class="detail-back-btn" aria-label="Kembali">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
        </svg>
      </button>
      <div class="detail-header-text">
        <h2>Tukar Poin</h2><p>Tukar poin kamu disini</p>
      </div>
    </div>
  </div>

  <div class="detail-content-grid">
    <div class="detail-card-left"><img id="detailProductImage" src="" alt="Produk Detail"></div>
    <div class="detail-card-right">
      <h1 id="detailProductTitle">Judul Produk</h1>
      <h3>Detail Produk</h3><p id="detailProductDesc" class="desc">Deskripsi produk...</p>
      <div class="detail-loc-box">
        <div class="detail-loc-icon">
          <svg viewBox="0 0 24 24" fill="#2BB6C4" width="40" height="40"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
        </div>
        <div class="detail-loc-text">
          <h4>Lokasi Mitra</h4><p>Jl. In dulu aja, No. 69, Bekasi Utara</p><a href="#">Pilih Lokasi</a>
        </div>
      </div>
      <div class="detail-btn-group">
        <button class="btn-keranjang-detail">Keranjang</button>
        <button class="btn-tukar-detail" onclick="window.location.href='#'">Tukar 30 Poin</button>
      </div>
    </div>
  </div>
</div>

<div id="cartView">
  <main class="container">
    <div class="page-header-card">
      <button id="btnBackCart" class="page-back-btn" aria-label="Kembali">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
      </button>
      <div class="page-header-text">
        <h2>Keranjang</h2><p>Barang yang kamu simpan</p>
      </div>
    </div>
    <div id="emptyCartState" class="empty-cart-container"><img src="../Home/img/Group 166.png" alt="Kosong"></div>
    <div id="filledCartState" style="display: none;"><div id="cartList" class="cart-list"></div></div>
  </main>
</div>

<div id="historyView">
  <main class="container">
    <div class="page-header-card">
      <button id="btnBackHistory" class="page-back-btn" aria-label="Kembali">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
      </button>
      <div class="page-header-text">
        <h2>Riwayat Setoran</h2><p>Popok yang kamu setor</p>
      </div>
    </div>
  </main>
</div>

<div class="modal-overlay" id="aboutPopup">
  <div class="modal-content">

    <button class="close-btn" id="closePopupBtn" aria-label="Tutup">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </button>

    <div class="modal-body">
      <h1 class="modal-title">Tentang Website</h1>

      <div class="desc-box">
        Website manajemen limbah popok berbasis website dengan mengintegrasikan ekonomi sirkular melalui penukaran popok menjadi poin dan poin ditukar menjadi produk dimana popok nantinya akan diolah menjadi produk bernilai seperti pupuk dan lain lain. Hal ini selaras dengan Konsep Pembangunan Berkelanjutan atau Sustainable Development Goals (SDGs)
      </div>

      <div class="sdg-image-wrapper">
        <img src="../Home/img/SDGSS.png" alt="Sustainable Development Goals" class="sdg-img">
      </div>

    </div>

    <div class="modal-footer">
      <div class="modal-footer-left">
        © 2026 PuPoP. Towards a Zero-Waste Future.
      </div>
      <div class="modal-footer-right">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">SDG Commitment</a>
        <a href="#">Contact</a>
      </div>
    </div>

  </div>
</div>

<script>
  const mainNavbar = document.getElementById('mainNavbar');
  const mainDashboard = document.getElementById('mainDashboardView');
  const detailView = document.getElementById('detailView');
  const cartView = document.getElementById('cartView');
  const historyView = document.getElementById('historyView');

  const navLinks = document.querySelectorAll('.nav-links a');
  const navHome = document.getElementById('navHome');
  const navCart = document.getElementById('navCart');
  const navHistory = document.getElementById('navHistory');

  const detailProductTitle = document.getElementById('detailProductTitle');
  const detailProductImage = document.getElementById('detailProductImage');
  const detailProductDesc = document.getElementById('detailProductDesc');

  let cart = [];

  function resetViews() {
    mainDashboard.style.display = 'none';
    detailView.style.display = 'none';
    cartView.style.display = 'none';
    historyView.style.display = 'none';
    navLinks.forEach(a => a.classList.remove('active'));
  }

  function openDashboard() {
    resetViews();
    mainDashboard.style.display = 'block';
    mainNavbar.style.display = 'flex';
    navHome.classList.add('active');
  }

  function openCart() {
    resetViews();
    cartView.style.display = 'block';
    mainNavbar.style.display = 'flex';
    navCart.classList.add('active');
    renderCart();
  }

  function openHistory() {
    resetViews();
    historyView.style.display = 'block';
    mainNavbar.style.display = 'flex';
    navHistory.classList.add('active');
  }


  navHome.addEventListener('click', (e) => { e.preventDefault(); openDashboard(); });
  navCart.addEventListener('click', (e) => { e.preventDefault(); openCart(); });
  navHistory.addEventListener('click', (e) => { e.preventDefault(); openHistory(); });

  document.querySelectorAll('.btn-lihat-produk').forEach(button => {
    button.addEventListener('click', function(e) {
      e.stopPropagation();
      const itemBox = this.closest('.item-box');

      const title = itemBox.querySelector('.item-title').innerText;
      const desc = itemBox.querySelector('.item-description').innerText;
      const imgSrc = itemBox.querySelector('.item-media img').src;

      detailProductTitle.innerText = title;
      detailProductDesc.innerText = desc;
      detailProductImage.src = imgSrc;

      resetViews();
      detailView.style.display = 'block';
      mainNavbar.style.display = 'none';

      window.scrollTo(0, 0);
    });
  });

  document.getElementById('btnBackDashboard').addEventListener('click', openDashboard);
  document.getElementById('btnBackCart').addEventListener('click', openDashboard);
  document.getElementById('btnBackHistory').addEventListener('click', openDashboard);

  document.querySelector('.btn-keranjang-detail').addEventListener('click', () => {
    const title = detailProductTitle.innerText;
    const desc = detailProductDesc.innerText;
    const imgSrc = detailProductImage.src;

    const existing = cart.find(i => i.title === title);
    if(existing) {
      existing.qty += 1;
    } else {
      cart.push({ title, desc, imgSrc, qty: 1 });
    }

    openCart();
  });

  function renderCart() {
    const emptyState = document.getElementById('emptyCartState');
    const filledState = document.getElementById('filledCartState');
    const cartList = document.getElementById('cartList');

    if(cart.length === 0) {
      emptyState.style.display = 'flex';
      filledState.style.display = 'none';
    } else {
      emptyState.style.display = 'none';
      filledState.style.display = 'block';

      cartList.innerHTML = '';
      cart.forEach((item, index) => {
        cartList.innerHTML += `
                <div class="cart-item">
                  <div class="cart-item-image">
                    <img src="${item.imgSrc}" alt="${item.title}">
                  </div>
                  <div class="cart-item-details">
                    <div class="cart-item-title">${item.title}</div>
                    <div class="cart-item-desc">${item.desc}</div>
                  </div>
                  <div class="cart-item-quantity">
                    <span class="qty-value">${item.qty}</span>
                  </div>
                </div>
            `;
      });
    }
  }

  window.updateQty = function(index, delta) {
    cart[index].qty += delta;
    if(cart[index].qty <= 0) {
      cart.splice(index, 1);
    }
    renderCart();
  };


  document.addEventListener("DOMContentLoaded", function() {
    const popup = document.getElementById("aboutPopup");
    const closeBtn = document.getElementById("closePopupBtn");

    if (!sessionStorage.getItem('aboutPopupShown')) {
      setTimeout(() => {
        popup.style.display = "flex";
      }, 300);

      sessionStorage.setItem('aboutPopupShown', 'true');
    }

    closeBtn.addEventListener("click", function() {
      popup.style.display = "none";
    });

  });

</script>
</body>
</html>
