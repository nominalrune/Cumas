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
	user_groups {
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
		text notes "メモ"
	}
	contact_phones {
		int id
		int contact_id
		varchar(255) phone
		text notes "メモ"
	}
	inquiries {
		int id
		int category_id "問い合わせカテゴリ"
		int email_id "問い合わせ者"
		varchar(255) status "対応状況"
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

## entities
```mermaid
classDiagram
	class User {
		int id
		string name "ユーザ名"
		string email "メールアドレス"
		string password
		timestamp created_at
		timestamp updated_at
		Collection<Inquiry> inquiries
	}
```
```mermaid
classDiagram
	class Group {
		int id
		string name
		Group parent
		timestamp created_at
		timestamp updated_at
		Collection<Inquiry> inquiries
	}
```
```mermaid
classDiagram
	class UserGroup {
		int id
		User user_
		Group group_
	}
```
```mermaid
classDiagram
	class MailAccount {
		int id
		Group group_
		string name
		string host
		int port
		string username
		string password
	}
```
```mermaid
classDiagram
	class Contact {
		int id
		string name "連絡先名前"
		string notes "メモ"
	}
	class ContactEmail {
		int id
		Contact contact
		string email
		string notes "メモ"
	}
	class ContactPhone {
		int id
		Contact contact
		string phone
		string notes "メモ"
	}
```
```mermaid
classDiagram
	class Inquiry {
		int id
		Category category_id "問い合わせカテゴリ"
		Contact contact "問い合わせ者"
		string status "対応状況"
		Group department "担当部署"
		User agent_id "担当者"
		string notes "メモ"
		timestamp created_at
		timestamp updated_at
	}
```
```mermaid
classDiagram
	class Message {
		int id
		Inquiry inquiry "結びつく問い合わせ"
		ContactEmail email "送信者のメールアドレス"
		ContactPhone phone "送信者の電話番号, nullable"
		string file "メールファイル名"
		string message_id "メールのmessageId"
		string reference_id "返信メールの場合、返信元メールのmessageId"
		string subject "件名"
	}
```
```mermaid
classDiagram
	class Category {
		int id
		string name "カテゴリ名"
		Group group "所属グループ"
		Category parent "親カテゴリ"
	}
```
# Domain
## Category

```mermaid
classDiagram
	
```
## Contact
```mermaid
classDiagram
```

## Group
```mermaid
classDiagram
```

## Inquiry
```mermaid
classDiagram
```

## MailAccount
```mermaid
classDiagram
```

## Message
```mermaid
classDiagram
```

## User
```mermaid
classDiagram
```
