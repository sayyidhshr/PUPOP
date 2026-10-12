<?php
session_start();

if (empty($_SESSION['sudahlogin'])) {
  header("Location: ../Signup/signup.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'
  && isset($_POST['aksi'])
  && $_POST['aksi'] === 'hapus_akun') {

  $emailLogin = $_SESSION['email'] ?? '';
  $file = __DIR__ . '/../users.json';

  if ($emailLogin === '' || !is_file($file)) {
    die("Data akun tidak ditemukan.");
  }

  $isiFile = file_get_contents($file);
  $users = json_decode($isiFile, true);

  if (!is_array($users)) {
    die("Data pengguna tidak valid.");
  }

  $usersBaru = [];
  $akunDitemukan = false;

  foreach ($users as $user) {
    if (
      isset($user['email']) &&
      strcasecmp(trim($user['email']), trim($emailLogin)) === 0
    ) {
      $akunDitemukan = true;
      continue;
    }

    $usersBaru[] = $user;
  }

  if (!$akunDitemukan) {
    die("Akun tidak ditemukan dalam data pengguna.");
  }

  $hasil = file_put_contents(
    $file,
    json_encode(
      array_values($usersBaru),
      JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    ),
    LOCK_EX
  );

  if ($hasil === false) {
    die("Akun gagal dihapus. Silakan coba kembali.");
  }

  $_SESSION = [];

  if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();

    setcookie(
      session_name(),
      '',
      time() - 42000,
      $params['path'],
      $params['domain'],
      $params['secure'],
      $params['httponly']
    );
  }

  session_destroy();

  header("Location: ../Signup/signup.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil</title>
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

    #profileView { display: none; }

    .profile-card-main {
      background: #ffffff;
      border-radius: 28px;
      padding: 35px 40px;
      border: 1px solid rgba(226, 232, 240, 0.8);
      margin-bottom: 30px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .profile-top-info {
      display: flex;
      align-items: center;
      gap: 25px;
    }

    .avatar-wrapper {
      position: relative;
      width: 100px;
      height: 100px;
      border-radius: 50%;
      border: 3px solid #fff;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .avatar-wrapper img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
    }
    .verified-badge {
      position: absolute;
      bottom: 2px;
      right: 2px;
      width: 26px;
      height: 26px;
      background: #00796B;
      border: 2px solid #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
    }

    .user-info-text .name-row {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 6px;
    }
    .user-info-text h2 {
      font-size: 26px;
      font-weight: 700;
      color: #005B66;
    }
    .premium-tag {
      background-color: #E0F2F1;
      color: #00796B;
      font-size: 10px;
      font-weight: 700;
      padding: 4px 10px;
      border-radius: 12px;
      letter-spacing: 0.5px;
    }
    .location-row {
      display: flex;
      align-items: center;
      gap: 6px;
      color: #718096;
      font-size: 14px;
    }

    .profile-divider {
      border: none;
      border-top: 1px solid #E2E8F0;
      margin: 30px 0;
    }

    .profile-stats-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }
    .stat-block .s-val {
      font-size: 20px;
      font-weight: 700;
      color: #1a202c;
      margin-bottom: 4px;
    }
    .stat-block .s-label {
      font-size: 12px;
      color: #718096;
    }

    .settings-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      margin-bottom: 30px;
    }
    .setting-card {
      background: #ffffff;
      border-radius: 24px;
      padding: 30px;
      border: 1px solid rgba(226, 232, 240, 0.8);
      box-shadow: 0 4px 15px rgb(136 136 136 / 0.4);
      display: flex;
      flex-direction: column;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .setting-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgb(136 136 136 / 0.65);
    }
    .s-icon-bg {
      width: 42px;
      height: 42px;
      background: #E6F7F8;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #00796B;
      margin-bottom: 20px;
    }
    .setting-card h3 {
      font-size: 18px;
      font-weight: 700;
      color: #005B66;
      margin-bottom: 8px;
    }
    .setting-card p {
      font-size: 13px;
      color: #718096;
      line-height: 1.5;
    }

    .profile-bottom-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #F8FAFC;
      border-radius: 20px;
      padding: 16px 24px;
      margin-top: 20px;
    }
    .account-status {
      font-size: 14px;
      color: #718096;
    }
    .account-status span {
      color: #00796B;
      font-weight: 600;
    }
    .profile-actions {
      display: flex;
      gap: 16px;
    }
    .btn-outline {
      background: transparent;
      border: 1px solid #00796B;
      color: #00796B;
      padding: 10px 24px;
      border-radius: 30px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.2s;
    }
    .btn-outline:hover { background: #E6F7F8; }

    .btn-danger {
      background: #C62828;
      border: none;
      color: #fff;
      padding: 10px 24px;
      border-radius: 30px;
      font-weight: 600;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      transition: background 0.2s;
    }
    .btn-danger:hover { background: #b71c1c; }

    @media (max-width: 900px) {
      .detail-content-grid, .settings-grid {
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
      .profile-top-info {
        flex-direction: column;
        text-align: center;
      }
      .user-info-text .name-row {
        justify-content: center;
        flex-direction: column;
      }
      .profile-stats-row {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 15px;
      }
      .profile-bottom-bar {
        flex-direction: column;
        gap: 20px;
        text-align: center;
      }
    }

    /* Popup Edit Profil - tambahan, tidak mengubah tampilan profil utama */
    .profile-edit-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 10000;
      padding: 20px;
    }

    .profile-edit-modal {
      background: #ffffff;
      width: 100%;
      max-width: 520px;
      border-radius: 24px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      padding: 32px;
      position: relative;
      animation: profileModalMuncul 0.25s ease-out;
    }

    @keyframes profileModalMuncul {
      from { opacity: 0; transform: translateY(15px) scale(0.97); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .profile-edit-modal h2 {
      color: #005B66;
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 6px;
    }

    .profile-edit-modal .edit-subtitle {
      color: #718096;
      font-size: 13px;
      margin-bottom: 24px;
    }

    .profile-edit-form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .profile-edit-form label {
      color: #2d3748;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: -8px;
    }

    .profile-edit-form input {
      width: 100%;
      padding: 12px 14px;
      border: 1px solid #E2E8F0;
      border-radius: 12px;
      outline: none;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      color: #1A202C;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .profile-edit-form input:focus {
      border-color: #2BB6C4;
      box-shadow: 0 0 0 3px rgba(43, 182, 196, 0.12);
    }

    .profile-edit-actions {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      margin-top: 8px;
    }

    .profile-edit-cancel,
    .profile-edit-save {
      border-radius: 30px;
      padding: 10px 22px;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
    }

    .profile-edit-cancel {
      background: #ffffff;
      color: #00796B;
      border: 1px solid #00796B;
    }

    .profile-edit-save {
      background: #2BB6C4;
      color: #ffffff;
      border: none;
    }

    .profile-edit-save:hover {
      background: #1fa4b2;
    }

    @media (max-width: 600px) {
      .profile-edit-modal {
        padding: 24px 20px;
      }

      .profile-edit-actions {
        flex-direction: column-reverse;
      }

      .profile-edit-cancel,
      .profile-edit-save {
        width: 100%;
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
  <style>#profileView { display: block !important; }</style>
</head>
<body>
<header id="mainNavbar" class="navbar">
  <div class="logo-area"><a href="../Home/home.php"><img src="../Home/img/Group 126.png" alt="Logo" class="logo-img" style="height:50px;"></a></div>
  <nav class="nav-links">
    <a href="../Home/home.php" id="navHome">Home</a>
    <a href="../Home/home.php#cartView" id="navCart">Keranjang</a>
    <a href="../Home/home.php#historyView" id="navHistory">Riwayat</a>
  </nav>
  <div class="nav-actions">
    <div class="nav-icon-btn" aria-label="Notifikasi"><svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/></svg></div>
    <a class="nav-icon-btn active" href="profil.php" aria-label="Profil"><svg width="22" height="22" fill="currentColor" viewBox="0 0 16 16"><path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg></a>
  </div>
</header>
<div id="profileView">
  <main class="container">

    <div class="profile-card-main">
      <div class="profile-top-info">
        <div class="avatar-wrapper">
          <img src="../Home/img/Ellipse 21.png" alt="Profile Picture">
          <div class="verified-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
          </div>
        </div>
        <div class="user-info-text">
          <div class="name-row">
            <h2 id="profileName">Pelanggan</h2>
            <span class="premium-tag">PREMIUM MEMBER</span>
          </div>
          <div class="location-row">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            <span id="profileLocation">Jl. In dulu aja, No. 69, Bekasi Utara</span>
          </div>
        </div>
      </div>

      <hr class="profile-divider">

      <div class="profile-stats-row">
        <div class="stat-block">
          <div class="s-val">0</div>
          <div class="s-label">Total setor popok (kg)</div>
        </div>
        <div class="stat-block">
          <div class="s-val">0</div>
          <div class="s-label">Produk ditukar</div>
        </div>
        <div class="stat-block">
          <div class="s-val">2026</div>
          <div class="s-label">Bergabung sejak</div>
        </div>
      </div>
    </div>

    <div class="settings-grid">
      <div class="setting-card" id="btnEditProfile">
        <div class="s-icon-bg">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        </div>
        <h3>Informasi Akun</h3>
        <p>Kelola detail profil dan edit detail profil.</p>
      </div>

      <div class="setting-card">
        <div class="s-icon-bg">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
        <h3>Analisis Popok</h3>
        <p>Lihat data statistik dan hasil analisis penggunaan produk.</p>
      </div>

      <div class="setting-card">
        <div class="s-icon-bg">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
        </div>
        <h3>Bantuan</h3>
        <p>Pusat bantuan dan panduan penggunaan aplikasi.</p>
      </div>

      <div class="setting-card">
        <div class="s-icon-bg">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
        </div>
        <h3>Kebijakan Privasi</h3>
        <p>Informasi mengenai keamanan dan data pribadi Anda.</p>
      </div>
    </div>

    <div class="profile-bottom-bar">
      <div class="account-status">
        Status Akun: <span>Pelanggan Aktif</span>
      </div>


      <div class="profile-actions">
        <button type="button" class="btn-outline"
                onclick="window.location.assign('profil.php')">
          Ganti Akun
        </button>

        <button type="button" class="btn-danger"
                onclick="window.location.assign('profil.php')">
          Keluar
        </button>

        <form method="POST"
              action=""
              onsubmit="return confirm('Apakah kamu yakin ingin menghapus akun? Data akun akan dihapus secara permanen.');">

          <input type="hidden" name="aksi" value="hapus_akun">

          <button type="submit" class="btn-danger">
            Hapus Akun
          </button>

        </form>
      </div>

    </div>
  </main>
</div>

<div class="profile-edit-overlay" id="profileEditPopup">
  <div class="profile-edit-modal">
    <button class="close-btn" id="closeProfileEditBtn" aria-label="Tutup">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </button>

    <h2>Edit Profil</h2>
    <p class="edit-subtitle">Perbarui informasi profil Anda.</p>

    <form class="profile-edit-form" id="profileEditForm">
      <label for="editProfileName">Nama</label>
      <input type="text" id="editProfileName" required>

      <label for="editProfileLocation">Lokasi</label>
      <input type="text" id="editProfileLocation" required>

      <div class="profile-edit-actions">
        <button type="button" class="profile-edit-cancel" id="cancelProfileEditBtn">Batal</button>
        <button type="submit" class="profile-edit-save">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script>
  const profileEditPopup = document.getElementById('profileEditPopup');
  const btnEditProfile = document.getElementById('btnEditProfile');
  const closeProfileEditBtn = document.getElementById('closeProfileEditBtn');
  const cancelProfileEditBtn = document.getElementById('cancelProfileEditBtn');
  const profileEditForm = document.getElementById('profileEditForm');
  const profileName = document.getElementById('profileName');
  const profileLocation = document.getElementById('profileLocation');
  const editProfileName = document.getElementById('editProfileName');
  const editProfileLocation = document.getElementById('editProfileLocation');

  const savedProfile = JSON.parse(localStorage.getItem('pupopProfile')) || {
    name: profileName.innerText,
    location: profileLocation.innerText
  };
  profileName.innerText = savedProfile.name;
  profileLocation.innerText = savedProfile.location;

  function openProfileEdit() {
    editProfileName.value = profileName.innerText;
    editProfileLocation.value = profileLocation.innerText;
    profileEditPopup.style.display = 'flex';
  }
  function closeProfileEdit() { profileEditPopup.style.display = 'none'; }
  btnEditProfile.addEventListener('click', openProfileEdit);
  closeProfileEditBtn.addEventListener('click', closeProfileEdit);
  cancelProfileEditBtn.addEventListener('click', closeProfileEdit);
  profileEditPopup.addEventListener('click', function(e) {
    if (e.target === profileEditPopup) closeProfileEdit();
  });
  profileEditForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const updatedName = editProfileName.value.trim();
    const updatedLocation = editProfileLocation.value.trim();
    if (!updatedName || !updatedLocation) {
      alert('Nama dan lokasi wajib diisi.');
      return;
    }
    profileName.innerText = updatedName;
    profileLocation.innerText = updatedLocation;
    localStorage.setItem('pupopProfile', JSON.stringify({name: updatedName, location: updatedLocation}));
    closeProfileEdit();
  });
</script>
</body>
</html>
