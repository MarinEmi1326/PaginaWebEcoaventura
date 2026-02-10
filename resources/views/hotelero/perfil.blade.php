@php $hideNavbar = true; @endphp
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f6f4ee]">
    <div class="flex">
        {{-- SIDEBAR MODERNO (Copiado de habitaciones) --}}
        @include('hotelero.partials.sidebar')

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="flex-1 overflow-x-hidden" style="margin-left: 18rem;">
            {{-- HEADER TOP (Igual al de habitaciones) --}}
            <header class="bg-white/60 backdrop-blur border-b border-emerald-900/10 px-10 py-5 flex items-center justify-between">
                <div class="font-serif text-xl font-semibold text-emerald-950">Mi Perfil</div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="text-sm font-semibold text-slate-800">{{ Auth::user()->nombre }}</div>
                        <div class="text-xs text-slate-500 uppercase">{{ Auth::user()->rol }}</div>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-emerald-800 text-white flex items-center justify-center font-bold">
                        {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}
                    </div>
                </div>
            </header>

            <section class="px-10 py-10">
                <div class="max-w-4xl mx-auto">
                    {{-- TARJETA DE PERFIL --}}
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-8 border-b border-slate-100 flex items-center gap-6">
                            <div class="w-24 h-24 rounded-3xl bg-emerald-100 flex items-center justify-center text-3xl font-bold text-emerald-800">
                                {{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}{{ strtoupper(substr(Auth::user()->apaterno, 0, 1)) }}
                            </div>
                            <div>
                                <h1 class="text-3xl font-serif font-bold text-emerald-950">{{ Auth::user()->nombre }} {{ Auth::user()->apaterno }}</h1>
                                <p class="text-slate-500">{{ Auth::user()->correo }}</p>
                                <span class="inline-block mt-2 px-3 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">HOTELERO</span>
                            </div>
                        </div>

                        <div class="p-8">
                            <h2 class="text-lg font-semibold text-emerald-950 mb-6">Información Personal</h2>
                            <form action="{{ route('hotelero.perfil.update') }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Nombre Completo</label>
                                        <input type="text" value="{{ Auth::user()->nombre }} {{ Auth::user()->apaterno }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-500" disabled />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Correo Electrónico</label>
                                        <input type="email" value="{{ Auth::user()->correo }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-500" disabled />
                                    </div>
                                    <div>
                                        <label for="telefono" class="block text-sm font-medium text-slate-700 mb-2">Teléfono de Contacto</label>
                                        <input type="text" id="telefono" name="telefono" value="{{ $hotelero->telefono ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-200 outline-none transition" placeholder="Tu número de teléfono" />
                                    </div>
                                </div>

                                <div class="flex justify-end pt-4">
                                    <button type="submit" class="bg-emerald-800 text-white px-8 py-3 rounded-xl font-semibold hover:bg-emerald-900 transition shadow-lg shadow-emerald-900/20">
                                        Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>
@endsection