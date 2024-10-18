<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# アプリ名

DayTrip Planner

## 概要

今のシーズンに人気なスポットなどの情報を紹介し, 行きたいスポットを決定したときに, そのスポットを経由地、または目的地とする旅行プランを簡単に作成できるアプリです。他にも口コミ投稿や自分で作成した旅行プランの投稿ができます。

## 使用技術

PHP: 8.2.21

Laravel: 10.48.20

Tailwindcss: 3.4.13

デプロイ：Heroku

[![使用技術アイコン](https://skillicons.dev/icons?i=php,laravel,tailwind,heroku)](https://skillicons.dev)

## URL・テストユーザー

URL:[localhost:3000](https://oneday-trip-8e0ed0b84bcb.herokuapp.com/)

以下のEmailとPasswardを用いてアクセスしてください。Nameは自由に設定できますが、demoとしてください。
<markdown-accessiblity-table data-catalyst=""><table>
<thead>
<tr>
<th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">メール</font></font></th>
<th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">パスワード</font></font></th>
</tr>
</thead>
<tbody>
<tr>
<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">demo103927@gmail.com</font></font></a></td>
<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">demo103927</font></font></td>
</tr>
</tbody>
</table></markdown-accessiblity-table>

## 機能
1. **全体に共通する機能**
   - 検索機能: キーワード検索と絞り込み条件を設定してスポットを検索できる(みんなの旅行計画一覧画面のみ旅行計画の検索機能となっている)
   - 画像モーダル画面: 画像をクリックすると画像が大きく表示される
   - 詳細画面遷移ボタン: View Moreボタンを押すと各詳細画面へ遷移する
   - サイドバー機能: サイト右上のボタンをクリックするとサイドバーが表示される(サイドバーの項目は、ゲスト時は3項目、ログイン時は6項目)
     - マイページ項目 : お気に入り登録したスポットや作成した旅行計画、いいねした旅行計画を見ることができる
     - プロフィール項目: 名前やメールアドレス、パスワードの更新、ユーザーアイコンを設定することができる
     - 旅行プラン作成項目: 新規の旅行計画を作成できる
     - みんなの旅行計画項目: みんなが投稿した旅行計画を見ることができる(いいねするとマイページに登録され、自分の旅行計画としてリメイクすることもできる)
     - 口コミ投稿一覧項目: スポットごとの口コミを見ることができる
     - 設定
     - ログアウトボタン: ログアウト用(ゲストモードに戻る)
   - 履歴機能: サイト上部に遷移した履歴が5件表示される(5件以上は最初の履歴から削除される)
2. **ホーム周りの機能**
   - スライドショー: スポットの画像をスライドショーで表示(サイトの更新毎に画像がランダムに表示される)
   - ランキング遷移機能: 各ランキングのView Moreボタンを押すと各ランキング画面に遷移する
   - 新規登録とログインボタン: ゲスト時はサイト上部に新規登録ボタンとログインボタンが表示される
   - 
3. **スポット詳細画面周りの機能**
   - お気に入り機能: スポット名の横にある星ボタンを押すとお気に入り登録ができる
   - 口コミ投稿機能: スポットに対する口コミを投稿できる
   - Map表示機能: Mapボタンを押すとスポット周辺の地図が表示される
   - カテゴリーごとの遷移機能: カテゴリーごとの項目をクリックすると、そのカテゴリーを含むスポット一覧に遷移する 
4. **口コミ投稿周りの機能**
    - いいね機能: 口コミに対していいねができる
    - レビュー機能: 5段階評価(小数で入力)
    - コメント機能: 口コミコメント
    - 画像投稿とプレビュー画面 : 画像を投稿する際に画像のプレビューを表示、画像ごとに表示する名前を付けられる。名前を付けない場合、imageと表示される
    - 
## 工夫点・苦労した点


## 今後の展望

## ER図・ワイヤーフレームなど
URL or スクショ（こっちの方がよい）

## Author

* 作成者: Yagyu Takuma
* 所属 : Gunma University
* E-mail : j241a031@gunma-u.ac.jp
