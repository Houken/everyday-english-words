<script setup>
import dayjs from "dayjs";
import ja from 'dayjs/locale/ja';
dayjs.locale(ja);

import convertEnglish from './helpers'

const props = defineProps({
    'section': Number,
    'testTypeIsRead': Boolean,
    'testBookName': String,
    'testHintLevel': Number,
    'testQuestions': Number,
    'testRangeFrom': Number,
    'testRangeTo': Number,
    'splitTest': Array,
})
</script>

<template>
    <div id="sidebar"
                class="fixed flex flex-col mr-4 font-bold t-0 min-w-fit max-w-fit gap-y-[.75rem] basis-1/4 text-slate-600">
                <div id="sitetitle"
                    class="p-6 text-2xl font-black text-center rounded-lg text-tealblack outline outline-2 outline-white -outline-offset-8 bg-tealgray">
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
                                <option v-for="indexFrom in testStyle.indexesFrom" :value="indexFrom">{{ indexFrom }}</option>
                            </select>
                            <!-- /# -->
                        </div>
                        <div class="px-2 pt-4 pb-6 bg-grassyellow rounded-br-md rounded-tr-md">
                            <h4 class="text-sm text-left">To:</h4>
                            <select name="testRangeTo" id="testRangeTo" class="w-full px-4 py-2 text-sm"
                                v-model.number="rangeToInput" @change="checkRangeToInput">
                                <option v-for="indexTo in testStyle.indexesTo" :value="indexTo">{{ indexTo }}</option>
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
</template>
