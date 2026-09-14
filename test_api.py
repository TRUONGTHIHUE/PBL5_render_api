import requests


# Thay TEN-DICH-VU bang URL Render sau khi deploy.
API_URL = "https://TEN-DICH-VU.onrender.com/product.php"

params = {
    "id": "STORE001",
    "product": "8934673502351",
}

try:
    response = requests.get(API_URL, params=params, timeout=90)

    print("URL da gui:", response.url)
    print("HTTP status:", response.status_code)
    print("Ket qua:")
    print(response.text)

    if response.headers.get("Content-Type", "").startswith("application/json"):
        data = response.json()
        if data.get("success"):
            print("Ten san pham:", data.get("name"))
            print("Gia:", data.get("price"))
            print("Vi tri:", data.get("location"))
            print("Han su dung:", data.get("expiry_date"))

except requests.exceptions.RequestException as error:
    print("Khong ket noi duoc API:")
    print(error)
