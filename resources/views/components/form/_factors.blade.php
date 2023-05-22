下のフォームが増えたらいいのになぁ
<!-- デザイン用 -->
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<!-- フォームが増えてくれる用 -->
<script defer src="https://unpkg.com/alpinejs@3.8.1/dist/cdn.min.js"></script>

<div x-data="inputFormHandler()" class="my-2">
    <!-- 要素名、要素の重み -->
    <template x-for="(field, i) in fields" :key="i">
        <div class="w-full flex my-2">
            <label :for="field.id" class="border border-gray-300 rounded-md p-2 w-full bg-white cursor-pointer">
                <span class="text-gray-700">要素名</span>
                <input type="text" :id="field.name_id" name="factor_names[]">
            </label>
            <label :for="field.id" class="border border-gray-300 rounded-md p-2 w-full bg-white cursor-pointer">
                <span class="text-gray-700">重み</span>
                <input type="text" :id="field.weight_id" name="factor_weights[]" @change="fields[i].file = $event.target.files[0]"> 
            </label>
            <button type="reset" @click="removeField(i)" class="p-2">
                ×
            </button>
        </div>
    </template>
    <!-- <template x-if="field.length is not null"> -->
        <button type="button" @click="addField()" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-500 hover:bg-gray-600">
            <span>要素を追加</span>
        </button>
    <!-- </template> -->
</div>

<script>
    function inputFormHandler(){
        return {
            fields: [],
            addField() {
                const i = this.fields.length;
                this.fields.push({
                    name_id: 'input-name-${i}',
                    weight_id: 'input-weight-${i}'
                });
            },
            removeField(index){
                this.fields.splice(index, 1);
            }
        }
    }
</script>


