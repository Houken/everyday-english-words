<script setup>
import AuthenticatedTestLayout from '@/Layouts/AuthenticatedTestLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    'bookId': { type: Number, default: 3 },
    'typeIsRead': { type: Boolean, default: true },
    'hintLevel': { type: Number, default: 2 },
    'rangeFrom': { type: Number, default: 1 },
    'rangeTo': { type: Number, default: 100 },
    'questions': { type: Number, default: 25 },
    'maxIndex': { type: Number, default: 1800 },
    'tests': Object,
})

const testBookId = ref(props.bookId)
const testTypeIsRead = ref(props.typeIsRead)
const testHintLevel = ref(props.hintLevel)
const testRangeFrom = ref(props.rangeFrom)
const testRangeTo = ref(props.rangeTo)
const testQuestions = ref(props.questions)
const rangeFromInput = ref(testRangeFrom)
const rangeToInput = ref(testRangeTo)

const checkRangeFromInput = () => {
    let from = rangeFromInput.value
    let to = rangeToInput.value
    rangeToInput.value = from > to ? Math.min(to + 100, props.maxIndex) : to
    testRangeFrom.value = from
    testRangeTo.value = to
}

watch([
    testBookId,
    testTypeIsRead,
    testHintLevel,
    testRangeFrom,
    testRangeTo,
    testQuestions
], (cr, prev) => {

    console.log(cr, prev);
    router.get(route('word.test', {
        testBookId: cr[0],
        testTypeIsRead: cr[1],
        testHintLevel: cr[2],
        testRangeFrom: cr[3],
        testRangeTo: cr[4],
        testQuestions: cr[5],
    }))
})
</script>

<template>
    <Head title="単語テスト" />
    <AuthenticatedTestLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">単語テスト</h2>
        </template>
        <!-- {{ tests }} -->
        <div class="flex p-4 bg-greenyslate">
            <div id="sidebar" class="flex flex-col mr-4 font-bold min-w-fit max-w-fit gap-y-4 basis-1/4 text-slate-600">
                <div id="sitetitle"
                    class="p-6 text-2xl font-black text-center rounded-lg text-tealblack outline outline-2 outline-white outline-offset-4 bg-tealgray">
                    まいにち単語テスト</div>
                <div id="books">
                    <h3 class="text-sm text-white text-outline drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">Book:</h3>
                    <div class="grid grid-cols-2 text-sm drop-shadow-md">
                        <button class="px-4 py-6 border-b border-r rounded-tl-md border-greenyslate" :class="{
                            'bg-grass text-black': testBookId == 3,
                            'bg-grassgray hover:text-black hover:bg-green-50': testBookId !== 3,
                        }" :disabled="testBookId == 3" @click="testBookId = 3">
                            中学1800</button>
                        <button class="px-4 py-6 border-b rounded-tr-md border-greenyslate" :class="{
                            'bg-grass text-black': testBookId == 1,
                            'bg-grassgray hover:text-black hover:bg-green-50': testBookId !== 1,
                        }" :disabled="testBookId == 1" @click="testBookId = 1">
                            ターゲット1900</button>
                        <button class="px-4 py-6 border-r rounded-bl-md border-greenyslate" :class="{
                            'bg-grass text-black': testBookId == 2,
                            'bg-grassgray hover:text-black hover:bg-green-50': testBookId !== 2,
                        }" :disabled="testBookId == 2" @click="testBookId = 2">
                            ターゲット1400</button>
                        <button class="px-4 py-6 rounded-br-md" :class="{
                            'bg-grass text-black': testBookId == 4,
                            'bg-grassgray hover:text-black hover:bg-green-50': testBookId !== 4,
                        }" :disabled="testBookId == 4" @click="testBookId = 4">システム2000</button>
                    </div>
                </div>
                <div id="types">
                    <h3 class="z-auto text-sm text-white text-outline drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">Type:
                    </h3>
                    <div class="relative z-50 grid grid-cols-2 text-lg drop-shadow-md">
                        <button class="px-4 py-8 border-r rounded-bl-md rounded-tl-md border-greenyslate" :class="{
                            'bg-pink-200 text-black': testTypeIsRead,
                            'hover:text-black bg-pink-100 hover:bg-red-50': !testTypeIsRead,
                        }" :disabled="testTypeIsRead" @click="testTypeIsRead = true">
                            Read</button>
                        <button class="px-4 py-8 rounded-br-md rounded-tr-md" :class="{
                            'bg-pink-200 text-black': !testTypeIsRead,
                            'hover:text-black bg-pink-100 hover:bg-red-50': testTypeIsRead,
                        }" :disabled="!testTypeIsRead" @click="testTypeIsRead = false">Write</button>
                    </div>
                    <div id="hintslider" class="z-0 p-2 text-center bg-fuchsia-50 rounded-b-md" :class="{
                        'transition -translate-y-16 duration-500 delay-100': testTypeIsRead,
                        'transition -translate-y-1 duration-500 delay-100': !testTypeIsRead,
                    }">
                        <h3>Hint Level</h3>
                        <input name="hint" type="range" min="0" max="2" step="1" v-model.number="testHintLevel">
                    </div>
                </div>
                <div id="range">
                    <h3 class="text-sm text-white text-outline drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">Range:</h3>
                    <div class="grid grid-cols-2 text-center drop-shadow-md">
                        <div class="px-2 pt-4 pb-6 border-r bg-grassyellow rounded-bl-md rounded-tl-md border-greenyslate">
                            <h4 class="text-sm text-left">From:</h4>
                            <select name="testRangeFrom" id="testRangeFrom" class="w-full px-4 py-2 text-sm" v-model.number="rangeFromInput" @change="checkRangeFromInput">
                                <option value=1 :selected="testRangeFrom==1">1</option>
                                <option value=101 :selected="testRangeFrom==101">101</option>
                                <option value=201>201</option>
                                <option value=301>301</option>
                                <option value=401>401</option>
                                <option value=501>501</option>
                                <option value=601>601</option>
                                <option value=701>701</option>
                                <option value=801>801</option>
                                <option value=901>901</option>
                                <option value=1001>1001</option>
                                <option value=1101>1101</option>
                                <option value=1201>1201</option>
                                <option value=1301>1301</option>
                                <option value=1401>1401</option>
                                <option value=1501>1501</option>
                                <option value=1601>1601</option>
                                <option value=1701>1701</option>
                                <option value=1801>1801</option>
                            </select>
                            <!-- /# -->
                        </div>
                        <div class="px-2 pt-4 pb-6 bg-grassyellow rounded-br-md rounded-tr-md">
                            <h4 class="text-sm text-left">To:</h4>
                            <select name="testRangeTo" id="testRangeTo" class="w-full px-4 py-2 text-sm" v-model.number="testRangeTo">
                                <option value=100>100</option>
                                <option value=200>200</option>
                                <option value=300>300</option>
                                <option value=400>400</option>
                                <option value=500>500</option>
                                <option value=600>600</option>
                                <option value=700>700</option>
                                <option value=800>800</option>
                                <option value=900>900</option>
                                <option value=1000>1000</option>
                                <option value=1100>1100</option>
                                <option value=1200>1200</option>
                                <option value=1300>1300</option>
                                <option value=1400>1400</option>
                                <option value=1500>1500</option>
                                <option value=1600>1600</option>
                                <option value=1700>1700</option>
                                <option value=1800>1800</option>
                                <option value=1900>1900</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="Questions">
                    <h3 class="text-sm text-white text-outline drop-shadow-[0_1.2px_1.2px_rgba(0,0,0,0.8)]">Questions:
                    </h3>
                    <div class="grid grid-cols-3 text-lg drop-shadow-md">
                        <button
                            class="px-4 py-8 border-r rounded-bl-md rounded-tl-md border-greenyslate"
                            :class="{
                                'text-black bg-purplesmoke': testQuestions==25,
                                'hover:text-black hover:bg-purple-50 bg-purplegray': testQuestions!==25,
                            }"
                            :disabled="testQuestions==25"
                            @click="testQuestions=25">
                            25</button>
                        <button
                            class="px-4 py-8 border-r border-greenyslate"
                            :class="{
                                'text-black bg-purplesmoke': testQuestions==50,
                                'hover:text-black hover:bg-purple-50 bg-purplegray': testQuestions!==50,
                            }"
                            :disabled="testQuestions==50"
                            @click="testQuestions=50">
                            50</button>
                        <button
                            class="px-4 py-8 rounded-br-md rounded-tr-md"
                            :class="{
                                'text-black bg-purplesmoke': testQuestions==100,
                                'hover:text-black hover:bg-purple-50 bg-purplegray': testQuestions!==100,
                            }"
                            :disabled="testQuestions==100"
                            @click="testQuestions=100">
                            100</button>
                    </div>
                </div>
            </div>
            <div id="wordsheet" class="p-4 bg-white rounded basis-3/4">
                <div class="px-2 bg-yellow-50">
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
                        <h2 class="w-5/6 py-4 text-lg font-bold text-center">25問テスト (1〜100)</h2>
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
                                            <tr
                                                v-for="test in tests"
                                                :key="test.index_no"
                                                class="border-b dark:border-neutral-500">
                                                <td class="px-6 py-1 font-medium whitespace-nowrap">{{ test.index_no }}</td>
                                                <td class="px-6 py-2 whitespace-nowrap">{{ test.word.english }}</td>
                                                <td class="px-6 py-1 whitespace-nowrap"><span
                                                        class="inline-block w-6 p-1 mr-2 font-bold text-center border rounded text-xs/12">{{ test.word.part_of_speech.charAt(0) }}</span>{{ test.japanese }}
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
