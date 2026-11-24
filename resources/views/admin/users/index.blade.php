<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F]">
                    Usuarios y roles
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73]">
                    Asigna permisos de Administrador o Vendedor a cada cuenta registrada.
                </p>
            </div>

            <span class="inline-flex items-center self-start sm:self-auto px-3 py-1 rounded-full text-xs font-medium bg-white border border-black/[0.08] text-[#6E6E73] tabular-nums">
                {{ $users->total() }} {{ $users->total() === 1 ? 'usuario' : 'usuarios' }}
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
            @if (session('status'))
                <div class="rounded-2xl bg-emerald-50 border border-emerald-600/20 px-4 py-3 text-sm text-emerald-800 flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0 text-emerald-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->has('role'))
                <div class="rounded-2xl bg-rose-50 border border-rose-600/20 px-4 py-3 text-sm text-rose-800 flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0 text-rose-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ $errors->first('role') }}</span>
                </div>
            @endif

            <div class="apple-surface overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-black/[0.06]">
                        <thead>
                            <tr class="bg-[#F5F5F7]/70">
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73] tracking-[-0.01em]">
                                    Usuario
                                </th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73] tracking-[-0.01em]">
                                    Correo
                                </th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73] tracking-[-0.01em]">
                                    Rol actual
                                </th>
                                <th scope="col" class="px-5 py-3.5 text-right text-xs font-medium text-[#6E6E73] tracking-[-0.01em]">
                                    Cambiar rol
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.06] bg-white">
                            @forelse ($users as $managedUser)
                                <tr class="apple-row-hover">
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#F5F5F7] border border-black/[0.08] text-xs font-semibold text-[#1D1D1F]">
                                                {{ mb_strtoupper(mb_substr($managedUser->name, 0, 1)) }}
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-medium text-[#1D1D1F]">
                                                    {{ $managedUser->name }}
                                                </span>
                                                @if (auth()->user()->is($managedUser))
                                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-medium bg-[#F5F5F7] text-[#6E6E73] border border-black/[0.06]">
                                                        Tú
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-sm text-[#6E6E73]">
                                        {{ $managedUser->email }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $managedUser->role->badgeClasses() }}">
                                            {{ $managedUser->role->label() }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right text-sm">
                                        <form method="POST" action="{{ route('admin.users.role.update', $managedUser) }}" class="inline-flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')

                                            <select name="role" aria-label="Rol para {{ $managedUser->name }}" class="rounded-xl border border-black/15 bg-white py-1.5 ps-3 pe-8 text-sm text-[#1D1D1F] transition-[border-color,box-shadow] duration-150 focus:border-[#0071E3] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->value }}" @selected($managedUser->role === $role)>
                                                        {{ $role->label() }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <x-primary-button type="submit" class="py-1.5 px-3.5 text-xs">
                                                Guardar
                                            </x-primary-button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-sm text-[#6E6E73]">
                                        No hay usuarios registrados todavía.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="px-5 py-3.5 border-t border-black/[0.06] bg-[#F5F5F7]/40 tabular-nums">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
