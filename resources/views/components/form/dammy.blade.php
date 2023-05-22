<html>
<head>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="p-5">
        <h1 class="text-3xl mb-4">
            Alpine.js のCRUDサンプル
        </h1>
        <div x-data="article" x-init="getArticles" class="grid grid-cols-2 gap-7">
            <div>
                <table class="w-full text-sm mb-5">
                    <thead>
                    <tr>
                        <th class="border p-2">タイトル</th>
                        <th class="border p-2">本文</th>
                        <th class="border p-2">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- ① x-for でループしてます -->
                    <template x-for="article in articles">
                        <tr>
                            <td class="border px-2 py-1" x-text="article.title"></td>
                            <td class="border px-2 py-1" x-text="article.content"></td>
                            <td class="border px-2 py-1 text-right">
                                <button
                                    type="button"
                                    class="bg-yellow-500 text-yellow-50 rounded p-2 text-xs"
                                    @click="onEdit(article)">
                                    変更
                                </button>
                                <button
                                    type="button"
                                    class="bg-red-600 text-red-50 rounded p-2 text-xs"
                                    @click="onDelete(article)">
                                    削除
                                </button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>

                <!-- ② x-show は、直接タグにセットしても OK -->
                <button
                    type="button"
                    class="bg-blue-500 text-blue-50 rounded p-2 text-xs"
                    x-show="hasPrevPage"
                    @click="onMovePage('prev')">
                    前へ
                </button>
                <button
                    type="button"
                    class="bg-blue-500 text-blue-50 rounded p-2 text-xs"
                    x-show="hasNextPage"
                    @click="onMovePage('next')">
                    次へ
                </button>

            </div>
            <div>
                <div class="text-green-700 p-3 bg-green-300 rounded mb-3" x-show="resultMessage">
                    <!-- ③ x-text でデータを表示します -->
                    <span x-text="resultMessage"></span>
                </div>
                <div class="mb-3">
                    <label for="title">タイトル</label>
                    <br>
                    <!-- ④ x-model で双方向バインディングします -->
                    <input id="title" type="text" class="border w-full p-1" x-model="params.title">
                </div>
                <div class="mb-4">
                    <label for="content">本文</label>
                    <br>
                    <textarea id="content" rows="7" class="border w-full p-1" x-model="params.content"></textarea>
                </div>
                <!-- ⑤ @**** でイベントをセットできます -->
                <button type="submit" class="bg-purple-700 text-purple-50 p-2 rounded" x-show="isModeCreate" @click="onSubmit">登録する</button>
                <button type="submit" class="bg-blue-700 text-blue-50 p-2 rounded" x-show="isModeEdit" @click="onSubmit">変更する</button>
            </div>
        </div>
    </div>

    <!-- ⑥ ここに defer がないとうまくいきません -->
    <script defer src="https://unpkg.com/alpinejs@3.8.1/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.25.0/axios.min.js"></script>
    <script>

        const article = () => {

            return {

                // data
                mode: 'create', // `create` or `edit`
                params: {
                    title: '',
                    content: '',
                },
                articles: [],
                page: 1,
                resultMessage: '',
                hasPrevPage: false,
                hasNextPage: false,

                // methods
                getArticles() {

                    this.articles = [];
                    const url = '';
                    axios.get(url)
                        .then(response => {

                            this.articles = response.data.data;
                            this.hasPrevPage = response.data.prev_page_url !== null;
                            this.hasNextPage = response.data.next_page_url !== null;

                        });

                },
                onEdit(article) {

                    // ⑦ - 1 スプレッド構文を使うと省コードになります
                    this.params = { ...article }; // オブジェクトの複製
                    this.mode = 'edit';

                },
                onMovePage(mode) {

                    this.page += (mode === 'prev') ? -1 : 1;
                    this.getArticles();

                },
                onSubmit() {

                    if(confirm('送信します。よろしいですか？')) {

                        let url = '';
                        let additionalParams = {};

                        if(this.isModeCreate === true) {

                            url = '';

                        } else if(this.isModeEdit === true) {

                            const articleId = this.params.id;
                            url = ``;
                            additionalParams = { _method: 'PUT' };

                        }

                        // ⑦ - 2 スプレッド構文を使うと省コードになります
                        const data = { // オブジェクトの合体
                            ...this.params,
                            ...additionalParams
                        };

                        axios.post(url, data)
                            .then(response => {

                                if(response.data.result === true) {

                                    this.getArticles();

                                    this.params = {
                                        id: '',
                                        title: '',
                                        content: '',
                                    };
                                    this.resultMessage = '保存が完了しました！';

                                    setTimeout(() => { // 3 秒後にメッセージをクリア

                                        this.resultMessage = '';

                                    }, 3000);

                                }

                            });

                    }

                },
                onDelete(article) {

                    if (confirm('削除します。よろしいですか？')) {

                        const url = '';
                        axios.delete(url)
                            .then(response => {

                                if (response.data.result === true) {

                                    this.getArticles();

                                }

                            });

                    }
                },

                // Computed
                // ⑧ 実際はちょっと違いますが Computed の代わりにしてます
                get isModeCreate() {

                    return this.mode === 'create';

                },
                get isModeEdit() {

                    return this.mode === 'edit';

                }
            };

        };

    </script>

</body>
</html>