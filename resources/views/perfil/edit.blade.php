@extends('layouts.plantilla')

@section('titulo', 'Mi Perfil')

@section('contenido')
<div class="max-w-4xl mx-auto my-8 px-4 bg-white p-6 shadow rounded">

    @if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block font-bold">Nombre</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control">
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-bold">Biografía</label>
            <textarea name="bio" class="form-control">{{ old('bio', $user->bio) }}</textarea>
            @error('bio') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-bold">Teléfono</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
            @error('phone') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-bold">Dirección</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control">
            @error('address') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-bold">Avatar</label>

            @if($user->avatar)
            <img src="{{ asset('assets/imagenes/' . $user->avatar) }}" class="w-20 h-20 rounded-full mb-2" alt="Avatar">
            @else
            <img src="{{ asset('assets/logo/default-avatar.png') }}" class="w-20 h-20 rounded-full mb-2" alt="Avatar">
            @endif
            
            <input type="file" name="avatar" class="form-control-file">
            @error('avatar') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-4 mt-6">

            <button type="submit" class="btn btn-accent">Guardar cambios</button>
            <a href="{{ route('perfil.propio') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection