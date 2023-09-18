<script setup>
import { onMounted, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage, Link } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';

const page = usePage();

const props = defineProps({
    'masters': Object,
    'search': String,
    'selectedBookId': Number,
    'selectedIdx': Number,
    'selectedMeanings': Array,
})

page.props.search = page.props.search === '%' ? '' : page.props.search

const createNewWordMode = ref(false)

const newPartOfSpeech = ref('')

const bookId = ref(page.props.selectedBookId)

const selectedIdx = ref(page.props.selectedIdx ? page.props.selectedIdx : 1)

const searchText = ref(page.props.search)

const searchInput = ref(null)

const selectedMeaning = props.selectedMeanings[1];

const nextMeanings = computed(() => props.selectedMeanings.slice(2));

const changeBook = () => {
    router.get(route('meaning.edit', {
        book_id: bookId.value,
        searchText: searchText.value,
        selectedIdx: selectedIdx.value
    }));
}

onMounted(() => {
    searchText.value = props.selectedMeanings[1].english !== "" ? props.selectedMeanings[1].english : props.selectedMeanings[1].word.english;
})

const updateMeaning = (wordId, english) => {
    router.get(route('meaning.update', {
        id: selectedMeaning.id,
        word_id: wordId,
        book_id: bookId.value,
        japanese: selectedMeaning.japanese,
        index_no: selectedMeaning.index_no,
        english: english,
    }
    )
    );
}

const createNewWord = () => {
    createNewWordMode.value = true;
}

const storeNewWord = (newWordEnglish, newWordPartOfSpeech) => {
    router.post(route('word.store'), {
        newWordEnglish: newWordEnglish,
        newWordPartOfSpeech: newWordPartOfSpeech,
    })
    // console.log(newWordEnglish, newWordPartOfSpeech)
}
</script>

<template>
    <Head title="単語一覧" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">単語一覧</h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <div class="flex gap-4">
                            <div class="flex flex-col w-2/3 bg-blue-50">
                                <div class="flex flex-row justify-between p-2">
                                    <div>
                                        <label for="select-book" class="mr-1 text-sm text-gray-500">単語帳:</label>
                                        <select v-model="bookId" @change="changeBook" id="select-book" name="select-book">
                                            <option value=1>ターゲット1900</option>
                                            <option value=2>ターゲット1400</option>
                                            <option value=3>中学ターゲット1800</option>
                                            <option value=4>システム2000</option>
                                        </select>
                                    </div>
                                    <div class="flex flex-row">
                                        <label for="searchIdx" class="my-auto mr-1 text-sm text-gray-500">IndexNo.:</label>
                                        <input v-model="selectedIdx" name="searchIdx" id="searchIdx" type="number"
                                            @change="changeBook" />
                                    </div>
                                </div>
                                <table class="w-full text-sm table-fixed">
                                    <thead>
                                        <tr class="bg-blue-200">
                                            <td class="w-1/12 px-6 py-3">idx</td>
                                            <td class="w-1/6 px-6 py-3">単語</td>
                                            <td class="w-1/12 px-6 py-3"></td>
                                            <td class="w-5/12 px-6 py-3">意味</td>
                                            <td class="w-1/12 px-6 py-3">id</td>
                                            <td class="w-1/6 px-6 py-3"></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-gray-400 bg-blue-100">
                                            <td class="px-6 py-3">{{ selectedMeanings[0].index_no }}</td>
                                            <td class="px-6 py-3">{{ selectedMeanings[0].english }}</td>
                                            <td class="px-6 py-3">{{ selectedMeanings[0].word.part_of_speech.charAt(0) }}
                                            </td>
                                            <td class="px-6 py-3 truncate">{{ selectedMeanings[0].japanese }}</td>
                                            <td class="px-6 py-3">{{ selectedMeanings[0].word_id }}</td>
                                            <td class="px-6 py-3">{{ selectedMeanings[0].word.english }}</td>
                                        </tr>
                                        <tr class="bg-white border-b">
                                            <td class="px-6 py-3">{{ selectedMeanings[1].index_no }}</td>
                                            <td class="px-6 py-3">{{ selectedMeanings[1].english }}</td>
                                            <td class="px-6 py-3">{{ selectedMeanings[1].word.part_of_speech.charAt(0) }}
                                            </td>
                                            <td class="px-6 py-3 truncate">{{ selectedMeanings[1].japanese }}</td>
                                            <td class="px-6 py-3">{{ selectedMeanings[1].word_id }}</td>
                                            <td class="px-6 py-3">{{ selectedMeanings[1].word.english }}</td>
                                        </tr>
                                        <tr v-for="meaning in nextMeanings"
                                            class="text-gray-400 bg-blue-100 border-b border-blue-200">
                                            <td class="px-6 py-3">{{ meaning.index_no }}</td>
                                            <td class="px-6 py-3">{{ meaning.english }}</td>
                                            <td class="px-6 py-3">{{ meaning.word.part_of_speech.charAt(0) }}</td>
                                            <td class="px-6 py-3 truncate">{{ meaning.japanese }}</td>
                                            <td class="px-6 py-3">{{ meaning.word_id }}</td>
                                            <td class="px-6 py-3">{{ meaning.word.english }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex flex-col w-1/3 bg-red-50">
                                <div class="p-2">
                                    <span
                                        class="px-4 py-2 text-gray-600 bg-gray-100 border-gray-300 rounded hover:bg-white"><em
                                            class="text-xs">検索対象: </em><a
                                            :href="'https://ejje.weblio.jp/content/' + searchText" target="_blank"
                                            class="hover:font-bold">{{ searchText }}</a></span>
                                    <button as="button"
                                        class="px-4 py-2 mx-4 font-bold text-white bg-red-500 border border-red-700 rounded hover:bg-red-700"
                                        @click="createNewWord()">New...</button>
                                </div>
                                <div v-if="createNewWordMode" class="py-2">
                                    <span class="px-4 py-2 mx-1 text-gray-400 bg-gray-100 border">{{ searchText }}</span>
                                    <select name="select-part-of-speech" id="select-part-of-speech"
                                        v-model="newPartOfSpeech">
                                        <option value="" disabled selected style="display:none;">品詞を選択</option>
                                        <option value="名詞">名詞</option>
                                        <option value="代名詞">代名詞</option>
                                        <option value="動詞">動詞</option>
                                        <option value="形容詞">形容詞</option>
                                        <option value="副詞">副詞</option>
                                        <option value="前置詞">前置詞</option>
                                        <option value="接続詞">接続詞</option>
                                        <option value="助動詞">助動詞</option>
                                        <option value="冠詞">冠詞</option>
                                        <option value="疑問詞">疑問詞</option>
                                        <option value="間投詞">間投詞</option>
                                    </select>
                                    <button class="px-4 py-2 ml-4 bg-green-300 rounded-full"
                                        @click="storeNewWord(searchText, newPartOfSpeech)">Save</button>
                                    <!-- /# -->
                                </div>
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-red-200">
                                            <td class="w-1/2 px-6 py-3">単語</td>
                                            <td class="w-1/6 px-6 py-3"></td>
                                            <td class="w-1/6 px-6 py-3">id</td>
                                            <td class="w-1/6 px-6 py-3">Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="master in props.masters"
                                            class="bg-red-100 border-b border-red-200 hover:bg-white hover:text-red-900">
                                            <td class="px-6 py-3">{{ master.english }}</td>
                                            <td class="px-6 py-3">{{ master.part_of_speech.charAt(0) }}</td>
                                            <td class="px-6 py-3">{{ master.id }}</td>
                                            <td class="px-6 py-3 hover:font-bold">
                                                <Link type="button" as="button" class="text-blue-400"
                                                    @click.prevent="updateMeaning(master.id, master.english)">
                                                Copy</Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout></template>
