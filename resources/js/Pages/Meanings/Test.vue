<script setup>
import AuthenticatedTestLayout from '@/Layouts/AuthenticatedTestLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    'bookId': { type: Number, default: 3 },
    'typeIsRead': { type: Boolean, default: true },
    'hintLevel': { type: Number, default: 2 },
    'rangeFrom': { type: Number, default: 1 },
    'rangeTo': { type: Number, default: 100 },
    'questions': { type: Number, default: 25 },
    'maxIndex': { type: Number, default: 1800 },
    'tests': Object,
    'splitTests': Array,
    'indexesTo': Array,
    'indexesFrom': Array,
})

console.log(props.indexesFrom, props.indexesTo)

const testBookId = ref(props.bookId)
const testTypeIsRead = ref(props.typeIsRead)
const testHintLevel = ref(props.hintLevel)
const testRangeFrom = ref(props.rangeFrom)
const testRangeTo = ref(props.rangeTo)
const testQuestions = ref(props.questions)
const rangeFromInput = ref(props.rangeFrom)
const rangeToInput = ref(props.rangeTo)

const testQuestionsString = ref('')
const testTypeIsReadString = ref('')

watch(testQuestionsString, (q) => {
    testQuestions.value = Number(q)
})

watch(testTypeIsReadString, (q) => {
    testTypeIsRead.value = q === 'true'
})

const checkRangeFromInput = () => {
    let from = rangeFromInput.value
    let to = rangeToInput.value
    rangeToInput.value = from > to ? Math.min(from + 99, props.maxIndex) : to
    testRangeFrom.value = from
    testRangeTo.value = rangeToInput.value
}

const checkRangeToInput = () => {
    let from = rangeFromInput.value
    let to = rangeToInput.value
    rangeFromInput.value = from > to ? Math.max(to - 99, 1) : from
    testRangeTo.value = to
    testRangeFrom.value = rangeFromInput.value
}

watch([
    testBookId,
    testTypeIsRead,
    testHintLevel,
    testRangeFrom,
    testRangeTo,
    testQuestions
], (cr, prev) => {

    // console.log(cr, prev);
    router.get(route('word.test', {
        testBookId: cr[0],
        testTypeIsRead: cr[1],
        testHintLevel: cr[2],
        testRangeFrom: cr[3],
        testRangeTo: cr[4],
        testQuestions: cr[5],
    }))
})

const convertEnglish = (word) => {
    // console.log(testTypeIsRead.value, testHintLevel.value)
    if (!testTypeIsRead.value && testHintLevel.value === 2) {
        const firstChar = word.charAt(0);
        const remaining = word.slice(1);

        return firstChar + remaining.replace(/[a-zA-z]/g, ' _');

    } else if (!testTypeIsRead.value && testHintLevel.value === 1) {
        return word.replace(/[a-zA-z]/g, ' _');
    } else {
        return word
    }
}
</script>

<template>
    <Head title="単語テスト" />
    <AuthenticatedTestLayout>
        <template #header class="print:hidden">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">単語テスト</h2>
        </template>
        <div class="flex p-4 bg-greenyslate print:hidden">
            <div id="sidebar"
                class="fixed flex flex-col mr-4 font-bold t-0 min-w-fit max-w-fit gap-y-4 basis-1/4 text-slate-600">
                <div id="sitetitle"
                    class="p-6 text-2xl font-black text-center rounded-lg text-tealblack outline outline-2 outline-white outline-offset-4 bg-tealgray">
                    まいにち単語テスト</div>
                <div id="books" class="">
                    <h3 class="text-sm text-white text-outline drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">Book:</h3>
                    <div class="grid grid-cols-2 text-sm drop-shadow-md">
                        <input type="radio" name="types" id="book3" value=3 class="hidden" v-model="testBookId">
                        <label for="book3" class="px-4 py-6 text-center border-b border-r rounded-tl-md border-greenyslate"
                            :class="{
                                'bg-grass text-black': testBookId == 3,
                                'bg-grassgray hover:text-black hover:bg-green-50': testBookId !== 3,
                            }">
                            中学1800</label>
                        <input type="radio" name="types" id="book1" value=1 class="hidden" v-model="testBookId">
                        <label for="book1" class="px-4 py-6 text-center border-b rounded-tr-md border-greenyslate" :class="{
                            'bg-grass text-black': testBookId == 1,
                            'bg-grassgray hover:text-black hover:bg-green-50': testBookId !== 1,
                        }">
                            ターゲット1900</label>
                        <input type="radio" name="types" id="book2" value=2 class="hidden" v-model="testBookId">
                        <label for="book2" class="px-4 py-6 text-center border-r rounded-bl-md border-greenyslate" :class="{
                            'bg-grass text-black': testBookId == 2,
                            'bg-grassgray hover:text-black hover:bg-green-50': testBookId !== 2,
                        }">
                            ターゲット1400</label>
                        <input type="radio" name="types" id="book4" value=4 class="hidden" v-model="testBookId">
                        <label for="book4" class="px-4 py-6 text-center rounded-br-md" :class="{
                            'bg-grass text-black': testBookId == 4,
                            'bg-grassgray hover:text-black hover:bg-green-50': testBookId !== 4,
                        }">システム2000</label>
                    </div>
                </div>
                <div id="types" class="">
                    <h3 class="z-auto text-sm text-white text-outline drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">Type:
                    </h3>
                    <div class="relative z-50 grid grid-cols-2 text-lg drop-shadow-md">
                        <input type="radio" name="types" id="typeIsRead" value=true class="hidden"
                            v-model="testTypeIsReadString">
                        <label for="typeIsRead"
                            class="px-4 py-8 text-center border-r rounded-bl-md rounded-tl-md border-greenyslate" :class="{
                                'bg-pink-200 text-black': testTypeIsRead,
                                'hover:text-black bg-pink-100 hover:bg-red-50': !testTypeIsRead,
                            }">
                            Read</label>
                        <input type="radio" name="types" id="typeIsNotRead" value=false class="hidden"
                            v-model="testTypeIsReadString">
                        <label for="typeIsNotRead" class="px-4 py-8 text-center rounded-br-md rounded-tr-md" :class="{
                            'bg-pink-200 text-black': !testTypeIsRead,
                            'hover:text-black bg-pink-100 hover:bg-red-50': testTypeIsRead,
                        }">
                            Write</label>
                    </div>
                    <div id="hintslider" class="z-0 p-2 text-center bg-fuchsia-50 rounded-b-md" :class="{
                        'ease-in  -translate-y-16 duration-800 delay-500': testTypeIsRead,
                        'ease-in  -translate-y-1 duration-800 delay-500': !testTypeIsRead,
                    }">
                        <h3>Hint Level</h3>
                        <input name="hint" type="range" min="0" max="2" step="1" v-model.number="testHintLevel">
                    </div>
                </div>
                <div id="range" class="">
                    <h3 class="text-sm text-white text-outline drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">Range:</h3>
                    <div class="grid grid-cols-2 text-center drop-shadow-md">
                        <div class="px-2 pt-4 pb-6 border-r bg-grassyellow rounded-bl-md rounded-tl-md border-greenyslate">
                            <h4 class="text-sm text-left">From:</h4>
                            <select name="testRangeFrom" id="testRangeFrom" class="w-full px-4 py-2 text-sm"
                                v-model.number="rangeFromInput" @change="checkRangeFromInput">
                                <option v-for="indexFrom in indexesFrom" :value="indexFrom">{{ indexFrom }}</option>
                            </select>
                            <!-- /# -->
                        </div>
                        <div class="px-2 pt-4 pb-6 bg-grassyellow rounded-br-md rounded-tr-md">
                            <h4 class="text-sm text-left">To:</h4>
                            <select name="testRangeTo" id="testRangeTo" class="w-full px-4 py-2 text-sm"
                                v-model.number="rangeToInput" @change="checkRangeToInput">
                                <option v-for="indexTo in indexesTo" :value="indexTo">{{ indexTo }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="Questions">
                    <h3 class="text-sm text-white text-outline drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">Questions:
                    </h3>
                    <div class="grid grid-cols-3 text-lg drop-shadow-md">
                        <input type="radio" name="questions" id="questions25" value=25 class="hidden"
                            v-model="testQuestionsString">
                        <label for="questions25"
                            class="px-4 py-8 text-center border-r rounded-bl-md rounded-tl-md border-greenyslate" :class="{
                                'text-black bg-purplesmoke': testQuestions == 25,
                                'hover:text-black hover:bg-purple-50 bg-purplegray': testQuestions !== 25,
                            }">
                            25</label>
                        <input type="radio" name="questions" id="questions50" value=50 class="hidden"
                            v-model.number="testQuestionsString">
                        <label for="questions50" class="px-4 py-8 text-center border-r border-greenyslate" :class="{
                            'text-black bg-purplesmoke': testQuestions == 50,
                            'hover:text-black hover:bg-purple-50 bg-purplegray': testQuestions !== 50,
                        }">
                            50</label>
                        <input type="radio" name="questions" id="questions100" value=100 class="hidden"
                            v-model.number="testQuestionsString">
                        <label for="questions100" class="px-4 py-8 text-center rounded-br-md rounded-tr-md" :class="{
                            'text-black bg-purplesmoke': testQuestions == 100,
                            'hover:text-black hover:bg-purple-50 bg-purplegray': testQuestions !== 100,
                        }">
                            100</label>
                    </div>
                </div>
            </div>
            <div id="wordsheet" class="p-4 bg-white rounded ml-80 basis-3/4">
                <div class="px-2 bg-yellow-50 drop-shadow-lg">
                    <div class="flex flex-row text-sm">
                        <button
                            class="flex flex-row px-2 py-2 m-auto text-blue-500 align-middle bg-blue-100 border rounded hover:text-blue-900 hover:bg-sky-50 w-1/8 min-w-fit">
                            <strong>Print it!</strong>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                            </svg>
                        </button>
                        <h2 class="w-5/6 py-4 text-lg font-bold text-center">{{ testQuestions }}問テスト ({{ testRangeFrom }}〜{{
                            testRangeTo }})</h2>
                    </div>
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-1 sm:px-6 lg:px-8">
                                <div class="overflow-hidden">
                                    <table class="min-w-full text-xs font-light text-left">
                                        <thead
                                            class="font-black border-t-2 border-gray-300 border-y text-slate-800 dark:border-neutral-500">
                                            <tr>
                                                <th scope="col" class="w-1/12 px-6 py-4 pb-2">No.</th>
                                                <th scope="col" class="w-3/12 px-6 py-4 pb-2">単語</th>
                                                <th scope="col" class="w-8/12 px-6 py-4 pb-2">意味</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="test in tests" :key="test.index_no"
                                                class="border-b dark:border-neutral-500">
                                                <td class="px-6 py-1 font-medium whitespace-nowrap">{{ test.index_no }}</td>
                                                <td class="px-6 py-2 whitespace-nowrap">{{ test.word.english }}</td>
                                                <td class="px-6 py-1 whitespace-nowrap"><span
                                                        class="inline-block w-6 p-1 mr-2 font-bold text-center border rounded text-xs/12">{{
                                                            test.word.part_of_speech.charAt(0) }}</span>{{ test.japanese }}
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

        </div>
        <div id="wordSheet1">
            <div id="wordsheetforprint1q" class="hidden p-8 bg-white rounded w-a4w h-a4h print:block">
                <div class="">
                    <div class="flex flex-row text-sm">
                        <h2 class="w-5/6 py-4 text-lg font-bold text-center">{{ testQuestions }}問テスト ({{ testRangeFrom }}〜{{
                            testRangeTo }}) 1 / {{ testQuestions / 25 }}</h2>
                    </div>
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-1 sm:px-6 lg:px-8">
                                <div class="overflow-hidden">
                                    <table class="min-w-full text-xs font-light text-left">
                                        <thead
                                            class="font-black border-t-2 border-gray-300 border-y text-slate-800 dark:border-neutral-500">
                                            <tr>
                                                <th scope="col" class="w-1/12 px-6 py-4 pb-2">No.</th>
                                                <th scope="col" class="w-3/12 px-6 py-4 pb-2">単語</th>
                                                <th scope="col" class="w-8/12 px-6 py-4 pb-2">意味</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="test in splitTests[0]" :key="test.index_no"
                                                class="border-b dark:border-neutral-500">
                                                <td class="px-6 py-1 font-medium whitespace-nowrap">{{ test.index_no }}</td>
                                                <td class="px-6 py-2 whitespace-nowrap">{{ convertEnglish(test.word.english) }}</td>
                                                <td class="px-6 py-1 whitespace-nowrap"><span
                                                        class="inline-block w-6 p-1 mr-2 font-bold text-center border rounded text-xs/12">{{
                                                            test.word.part_of_speech.charAt(0) }}</span>{{ testTypeIsRead ? '' : test.japanese }}
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
            <div id="wordsheetforprint1a" class="hidden p-8 bg-white rounded w-a4w h-a4h print:block">
                <div class="">
                    <div class="flex flex-row text-sm">
                        <h2 class="w-5/6 py-4 text-lg font-bold text-center">{{ testQuestions }}問テスト ({{ testRangeFrom }}〜{{
                            testRangeTo }}) 1 / {{ testQuestions / 25 }} 正解</h2>
                    </div>
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-1 sm:px-6 lg:px-8">
                                <div class="overflow-hidden">
                                    <table class="min-w-full text-xs font-light text-left">
                                        <thead
                                            class="font-black border-t-2 border-gray-300 border-y text-slate-800 dark:border-neutral-500">
                                            <tr>
                                                <th scope="col" class="w-1/12 px-6 py-4 pb-2">No.</th>
                                                <th scope="col" class="w-3/12 px-6 py-4 pb-2">単語</th>
                                                <th scope="col" class="w-8/12 px-6 py-4 pb-2">意味</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="test in splitTests[0]" :key="test.index_no"
                                                class="border-b dark:border-neutral-500">
                                                <td class="px-6 py-1 font-medium whitespace-nowrap">{{ test.index_no }}</td>
                                                <td class="px-6 py-2 whitespace-nowrap">{{ test.word.english }}</td>
                                                <td class="px-6 py-1 whitespace-nowrap"><span
                                                        class="inline-block w-6 p-1 mr-2 font-bold text-center border rounded text-xs/12">{{
                                                            test.word.part_of_speech.charAt(0) }}</span>{{ test.japanese }}
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
        </div>
        <div id="woodSheet2" v-if="testQuestions > 25">
            <div id="wordsheetforprint2" class="hidden p-8 bg-white rounded w-a4w h-a4h print:block">
                <div class="">
                    <div class="flex flex-row text-sm">
                        <h2 class="w-5/6 py-4 text-lg font-bold text-center">{{ testQuestions }}問テスト ({{ testRangeFrom }}〜{{
                            testRangeTo }}) 2 / {{ testQuestions / 25 }}</h2>
                    </div>
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-1 sm:px-6 lg:px-8">
                                <div class="overflow-hidden">
                                    <table class="min-w-full text-xs font-light text-left">
                                        <thead
                                            class="font-black border-t-2 border-gray-300 border-y text-slate-800 dark:border-neutral-500">
                                            <tr>
                                                <th scope="col" class="w-1/12 px-6 py-4 pb-2">No.</th>
                                                <th scope="col" class="w-3/12 px-6 py-4 pb-2">単語</th>
                                                <th scope="col" class="w-8/12 px-6 py-4 pb-2">意味</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="test in splitTests[1]" :key="test.index_no"
                                                class="border-b dark:border-neutral-500">
                                                <td class="px-6 py-1 font-medium whitespace-nowrap">{{ test.index_no }}</td>
                                                <td class="px-6 py-2 whitespace-nowrap">{{ test.word.english }}</td>
                                                <td class="px-6 py-1 whitespace-nowrap"><span
                                                        class="inline-block w-6 p-1 mr-2 font-bold text-center border rounded text-xs/12">{{
                                                            test.word.part_of_speech.charAt(0) }}</span>{{ test.japanese }}
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
        </div>
        <div id="woodSheet3" v-if="testQuestions > 50">
            <div id="wordsheetforprint3" class="hidden p-8 bg-white rounded w-a4w h-a4h print:block">
                <div class="">
                    <div class="flex flex-row text-sm">
                        <h2 class="w-5/6 py-4 text-lg font-bold text-center">{{ testQuestions }}問テスト ({{ testRangeFrom }}〜{{
                            testRangeTo }}) 3 / {{ testQuestions / 25 }}</h2>
                    </div>
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-1 sm:px-6 lg:px-8">
                                <div class="overflow-hidden">
                                    <table class="min-w-full text-xs font-light text-left">
                                        <thead
                                            class="font-black border-t-2 border-gray-300 border-y text-slate-800 dark:border-neutral-500">
                                            <tr>
                                                <th scope="col" class="w-1/12 px-6 py-4 pb-2">No.</th>
                                                <th scope="col" class="w-3/12 px-6 py-4 pb-2">単語</th>
                                                <th scope="col" class="w-8/12 px-6 py-4 pb-2">意味</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="test in splitTests[2]" :key="test.index_no"
                                                class="border-b dark:border-neutral-500">
                                                <td class="px-6 py-1 font-medium whitespace-nowrap">{{ test.index_no }}</td>
                                                <td class="px-6 py-2 whitespace-nowrap">{{ test.word.english }}</td>
                                                <td class="px-6 py-1 whitespace-nowrap"><span
                                                        class="inline-block w-6 p-1 mr-2 font-bold text-center border rounded text-xs/12">{{
                                                            test.word.part_of_speech.charAt(0) }}</span>{{ test.japanese }}
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
        </div>
        <div id="woodSheet4" v-if="testQuestions > 50">
            <div id="wordsheetforprint4" class="hidden p-8 bg-white rounded w-a4w h-a4h print:block">
                <div class="">
                    <div class="flex flex-row text-sm">
                        <h2 class="w-5/6 py-4 text-lg font-bold text-center">{{ testQuestions }}問テスト ({{ testRangeFrom }}〜{{
                            testRangeTo }}) 4 / {{ testQuestions / 25 }}</h2>
                    </div>
                    <div class="flex flex-col">
                        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-1 sm:px-6 lg:px-8">
                                <div class="overflow-hidden">
                                    <table class="min-w-full text-xs font-light text-left">
                                        <thead
                                            class="font-black border-t-2 border-gray-300 border-y text-slate-800 dark:border-neutral-500">
                                            <tr>
                                                <th scope="col" class="w-1/12 px-6 py-4 pb-2">No.</th>
                                                <th scope="col" class="w-3/12 px-6 py-4 pb-2">単語</th>
                                                <th scope="col" class="w-8/12 px-6 py-4 pb-2">意味</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="test in splitTests[3]" :key="test.index_no"
                                                class="border-b dark:border-neutral-500">
                                                <td class="px-6 py-1 font-medium whitespace-nowrap">{{ test.index_no }}</td>
                                                <td class="px-6 py-2 whitespace-nowrap">{{ test.word.english }}</td>
                                                <td class="px-6 py-1 whitespace-nowrap"><span
                                                        class="inline-block w-6 p-1 mr-2 font-bold text-center border rounded text-xs/12">{{
                                                            test.word.part_of_speech.charAt(0) }}</span>{{ test.japanese }}
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
        </div>
    </AuthenticatedTestLayout>
</template>
