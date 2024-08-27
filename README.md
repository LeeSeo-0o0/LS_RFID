# 💼 NFC 명함 관리 시스템

이 프로젝트는 NFC 카드를 이용한 사용자 명함 관리 시스템입니다. 사용자는 NFC 카드를 스캔하여 자동으로 자신의 명함 페이지로 리디렉션되며, 관리자는 사용자 정보를 관리하고 명함 페이지를 생성할 수 있습니다.

## 📋 주요 기능

- **관리자 계정 생성**: 첫 설정 시 데이터베이스 정보와 관리자 계정을 생성합니다.
- **사용자 명함 생성**: 관리자는 사용자의 명함 정보를 입력하여 새로운 명함을 생성할 수 있습니다.
- **NFC 카드 리디렉션**: NFC 카드를 스캔하면 사용자의 명함 페이지로 자동 리디렉션됩니다.
- **명함 정보 관리**: 관리자는 사용자 정보를 수정하거나 삭제할 수 있습니다.

## 🛠️ 설치 방법

1. 이 저장소를 클론합니다:

   ```bash
   git clone https://github.com/LeeSeo-0o0/LS_RFID.git
프로젝트 디렉토리로 이동합니다:

코드 복사<br/>
cd LS_RFID<br/>
웹 서버에 프로젝트를 배포합니다.<br/>

웹 브라우저에서 index.php에 접속하여 초기 설정을 완료합니다:<br/>

데이터베이스 정보 입력<br/>
관리자 계정 생성<br/>
🚀 사용 방법<br/>
관리자 로그인: admin.php에 접속하여 관리자 계정으로 로그인합니다.<br/>
사용자 생성: 사용자의 명함 정보를 입력하여 새 사용자를 생성합니다.<br/>
NFC 카드 설정: NFC 카드에 localhost/redirect.php?id=사용자ID 형식의 URL을 기록합니다.<br/>
명함 페이지 접속: NFC 카드를 스캔하면 자동으로 해당 사용자의 명함 페이지로 이동됩니다.<br/>
📂 프로젝트 구조<br/>
<br/>
코드 복사<br/>
📁 프로젝트 루트<br/>
├── 📄 index.php          # 초기 설정 및 관리자 계정 생성 페이지<br/>
├── 📄 admin.php          # 관리자 패널<br/>
├── 📄 register.php       # (옵션) 사용자 명함 등록 페이지<br/>
├── 📄 redirect.php       # NFC 카드 리디렉션 처리 파일<br/>
├── 📄 card.php           # 사용자 명함 페이지<br/>
├── 📄 dbconfig.php       # 데이터베이스 설정 파일 (자동 생성)<br/>
└── 📁 assets             # 필요한 경우 추가적인 정적 파일 디렉토리<br/>
💡 주의사항<br/>
보안: dbconfig.php 파일은 데이터베이스 정보를 포함하므로, 외부에 노출되지 않도록 주의하세요.<br/>
백업: 중요한 사용자 데이터가 포함될 수 있으므로 정기적인 데이터베이스 백업을 권장합니다.<br/>
📝 라이선스<br/>
이 프로젝트는 MIT 라이선스에 따라 배포됩니다.<br/>
<br/>
📧 문의<br/>
프로젝트에 대한 문의는 help@leeseo.kr으로 연락해 주세요.<br/>

1.1버전
![image](https://github.com/user-attachments/assets/cd54ec26-6c3c-4869-8c55-bc69219e638b)

1.0 버전
![image](https://github.com/user-attachments/assets/88983ee6-9f22-424b-8215-32b39fae2f70)
![image](https://github.com/user-attachments/assets/c166410b-0f4c-43a7-bca7-97e01ccec34d)
