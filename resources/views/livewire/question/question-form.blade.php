<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $editing ? 'Edit Question' : 'Create Question' }}
        </h2>
    </x-slot>

    <x-slot name="title">
        {{ $editing ? 'Edit Question ' . $question->id : 'Create Question' }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit.prevent="save">
                        <div>
                            <x-input-label for="text" value="Question text" />
                            <x-textarea wire:model.defer="question.text" id="text" class="block mt-1 w-full"
                                type="text" name="text" required />
                            <x-input-error :messages="$errors->get('question.text')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="options" value="Question options" />
                            @foreach ($options as $index => $option)
                                <div class="flex mt-2">
                                    <x-text-input type="text" wire:model.defer="options.{{ $index }}.text"
                                        class="w-full" name="options_{{ $index }}"
                                        id="options_{{ $index }}" autocomplete="off" />

                                    <div class="flex items-center">
                                        <input type="checkbox" class="mr-1 ml-4"
                                            wire:model.defer="options.{{ $index }}.correct"> Correct
                                        <button wire:click="removeOption({{ $index }})" type="button"
                                            class="ml-4 rounded-md border border-transparent bg-red-200 px-4 py-2 text-xs uppercase text-red-500 hover:bg-red-300 hover:text-red-700">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('options.' . $index . '.text')" class="mt-2" />
                            @endforeach

                            <x-input-error :messages="$errors->get('options')" class="mt-2" />

                            <x-primary-button wire:click="addOption" type="button" class="mt-2">
                                Add
                            </x-primary-button>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="code_snippet" value="Code snippet" />
                            <x-textarea wire:model.defer="question.code_snippet" id="code_snippet"
                                class="block mt-1 w-full" type="text" name="code_snippet" />
                            <x-input-error :messages="$errors->get('question.code_snippet')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="answer_explanation" value="Answer explanation" />
                            <x-textarea wire:model.defer="question.answer_explanation" id="answer_explanation"
                                class="block mt-1 w-full" type="text" name="answer_explanation" />
                            <x-input-error :messages="$errors->get('question.answer_explanation')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="more_info_link" value="More info link" />
                            <x-text-input wire:model.defer="question.more_info_link" id="more_info_link"
                                class="block mt-1 w-full" type="text" name="more_info_link" />
                            <x-input-error :messages="$errors->get('question.more_info_link')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-primary-button>
                                Save
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
