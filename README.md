1. Register

    POST http://localhost:8000/api/register
    Body: {
        "name": "name",
        "email": "yassir@example.com",
        "password": "exemple123",
    }

2. Login

    POST http://127.0.0.1:8000/api/login
    Body: {
        "email": "yassir@example.com",
        "password": "exemple123",
    }

3. LogOut ->copie le token reçu dans login ou register

    POST http://127.0.0.1:8000/api/logout
    Headers → Authorization: Bearer {ton_token}

4. See all Wallets

 GET http://127.0.0.1:8000/api/wallets

5. Add Wallet

   POST http://127.0.0.1:8000/api/wallets
    Body: {
        "name": "exemple Wallet",
        "currency": "MAD"
    }

6. Show Wallet by Id

 GET http://127.0.0.1:8000/api/wallets/{id}

7. Deposit

    POST http://127.0.0.1:8000/api/wallets/{id}/deposit
    Body: {
        "amount": 500.00,
        "description": "Dépôt initial"
    }

8. withdraw

    POST http://127.0.0.1:8000/api/wallets/{id}/withdraw
    Body: {
        "amount": 500.00,
        "description": "Retrait agrent"
    }

9. transfer

    POST http://127.0.0.1:8000/api/wallets/{id}/transfer
    Body: {
        "receiver_wallet_id": 3,
        "amount": 100.00,
        "description": "Remboursement déjeuner"
    }

10. historique


GET http://127.0.0.1:8000/api/wallets/{id}/transactions

