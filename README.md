# Cumas - A simple customer support management system
[![Build Status](https://travis-ci.org/lb-cumas/cumas.svg?branch=master)](https://travis-ci.org/lb-cumas/cumas)[![Coverage Status](https://coveralls.io/repos/github/lb-cumas/cumas/badge.svg?branch=master)](https://coveralls.io/github/lb-cumas/cumas?branch=master)![License](https://img.shields.io/badge/license-MIT-blue.svg)

cumas is a simple customer support management system. It is designed to be simple and easy to use. It is written in php symphony framework.

# instration

# features
1. gather customer inquiries from multiple mail accounts
1. assign inquiries to agents and track their progress
1. agents can interract with customers via embedded mail feature
1. manage customer information

# design
## database table(overview)
```mermaid
erDiagram
    users {
        int id
		varchar name "ユーザ名"
		varchar(255) email "メールアドレス"
		text password
		timestamp created_at
		timestamp updated_at
    }
	groups {
		int id
		varchar(255) name
		int parent_id
		timestamp created_at
		timestamp updated_at
	}
	groups_users {
		int id
		int user_id
		int group_id
	}
	mail_accounts {
		int id
		int group_id
		varchar(255) name
		text host
		int port
		text username
		text password
	}
	contacts {
		int id
		varchar(255) name "連絡先名前"
		text notes "メモ"
	}
	contact_emails {
		int id
		int contact_id
		varchar(255) email
		varchar(511) notes "メモ"
	}
	contact_phones {
		int id
		int contact_id
		varchar(255) phone
		varchar(1023) notes "メモ"
	}
	inquiries {
		int id
		int category_id "問い合わせカテゴリ"
		int email_id "問い合わせ者"
		int status "対応状況"
		int department_id "担当部署"
		int agent_id "担当者"
		text notes "メモ"
		timestamp created_at
		timestamp updated_at
	}
	messages {
		int id
		int inquiry_id "結びつく問い合わせ"
		int contact_email_id "送信者のメールアドレス"
		int contact_phone_id "送信者の電話番号, nullable"
		varchar(511) file "メールファイル名"
		varchar(511) message_id "メールのmessageId"
		varchar(511) reference_id "返信メールの場合、返信元メールのmessageId"
		varchar(1023) subject "件名"
	}
	categories {
		int id
		varchar(255) name "カテゴリ名"
		int group_id "所属グループ"
		int parent_id "親カテゴリ"
	}
	users ||--o{ groups_users :hasMany
	groups ||--o{ groups_users :hasMany
	groups ||--o{ groups :hasMany
	groups ||--o{ mail_accounts :hasMany
	inquiries }o--|| contacts:hasOne
	inquiries }o--|| messages:hasOne
	inquiries }o--o| users:belongsTo
	inquiries }o--|| groups:belongsTo
	inquiries }o--|| categories:belongsTo
	groups ||--o{ categories :hasMany
	contacts ||--o{ contact_emails :belongsTo
	contacts ||--o{ contact_phones:belongsTo
	categories |o--o{ categories :hasMany
```

## classes
```mermaid
classDiagram
    Animal <|-- Zebra
    class Duck{
      +String beakColor
      +swim()
      +quack()
    }
    class Fish{
      -int sizeInFeet
      -canEat()
    }
    class Zebra{
      +bool is_wild
      +run()
    }
```