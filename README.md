### masterブランチについて

| branch name    | description                           |
|:---------------|:--------------------------------------|
| master         | 利用しない                            |
| master-module  | MVNOモジュール共通APIマスターブランチ |
| module         | MVNOモジュール共通API開発用ブランチ   |

### Githubでの作業の進め方

#### 開発の手順

1. 開発をする機能をIssueに登録
1. 開発担当者は Issue に対しての開発を開始する際に、自身をassignして、 `doing` ラベルを付ける
1. `branch suffix`/`Issue Number` の branch を作成
ex) module/123, module/124
1. 開発を開始する
仕様に関する不明点があれば、Issue に `@eyemovic/system` 宛でコメントを追記する
1. 開発が完了したら、対応した Issue に `pull request` ラベルを付ける
1. branch を push
1. pull request を作成
pull request の コメントには `refs #19` のように関連する Issue Number を記載
1. 作成した pull request に `reviewable` ラベルを付ける
1. `@eyemovic/system` 宛にレビュー依頼のコメントを追記
1. コードレビュー
1. レビュー担当者は問題がなくなったら pull request に `mergeable` ラベルを付ける
__マージ作業や confrictの解消は都度、アイムービックの指示がない限りは実施しない__

#### バグ発見時の Issue の立て方
1. バグを発見した時は、発見者が Issue を立てる
開発中の機能に関するバグに関しては、該当する機能の Issue にコメントを追記する
1. `bug`ラベルをつける
1. 再現手順が明らかな場合は、再現手順を記載
   ※：具体的にコード上の不具合箇所が分かる場合は、不具合箇所を明記する
1. 画面のキャプチャーがあれば合わせて添付する

#### バグの対応について
1. バグの調査は1件につき、30分を目安とする。

#### その他
- 作成中の Issue や実装途中のPull Requestなどについては、`work in progress` ラベルを付ける
