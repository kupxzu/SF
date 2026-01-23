<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ArrowRight, PawPrint } from 'lucide-vue-next'
import { dashboard, login, register } from '@/routes'

withDefaults(
  defineProps<{
    canRegister: boolean
  }>(),
  { canRegister: true }
)

const brandIcon = '/icons/logo.png'
</script>

<template>
  <Head title="Sniffs & Licks Veterinary Clinic" />

  <!-- Light animated background -->
  <div class="relative min-h-screen overflow-hidden bg-slate-50 text-slate-800">
    <!-- soft animated shapes -->
    <div class="pointer-events-none absolute inset-0">
      <div class="blob blob-1" />
      <div class="blob blob-2" />
      <div class="noise" />
    </div>

    <!-- content -->
    <div class="relative mx-auto flex min-h-screen max-w-7xl items-center justify-center px-4">
      <div class="grid w-full max-w-5xl items-center gap-10 lg:grid-cols-2">

        <!-- LEFT : BRAND -->
        <div class="text-center lg:text-left">
          <div class="mx-auto mb-6 flex w-fit items-center gap-3 rounded-xl bg-white px-4 py-2 shadow-sm ring-1 ring-slate-200 lg:mx-0">
            <img
              :src="brandIcon"
              alt="Clinic Logo"
              class="h-9 w-9 rounded-lg object-contain"
              @error="$event.currentTarget.style.display='none'"
            />
            <PawPrint class="h-5 w-5 text-emerald-500" />
            <span class="text-sm font-semibold tracking-wide">
              Sniffs & Licks Veterinary Clinic
            </span>
          </div>

          <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl">
            gentle care for
            <span class="text-emerald-600">every pet</span>
          </h1>

          <p class="mt-4 max-w-xl text-base text-slate-600 lg:mx-0 mx-auto">
            #44 Maxlen Bldg. Maharlika Highway<br />
            Tanza, Tuguegarao City<br />
            <span class="text-slate-500">Ian Jay B. Vicente, DVM • +63 965 553 6159</span>
          </p>
        </div>

        <!-- RIGHT : LOGIN CARD -->
        <div class="mx-auto w-full max-w-md">
          <div class="rounded-3xl bg-white p-6 shadow-xl ring-1 ring-slate-200">
            <h2 class="text-xl font-bold">login</h2>
            <p class="mt-1 text-sm text-slate-500">
              access appointments and clinic records
            </p>

            <div class="mt-6 space-y-3">
              <Link
                v-if="$page.props.auth.user"
                :href="dashboard()"
                class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
              >
                go to dashboard
                <ArrowRight class="h-4 w-4 transition group-hover:translate-x-0.5" />
              </Link>

              <template v-else>
                <Link
                  :href="login()"
                  class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700"
                >
                  log in
                  <ArrowRight class="h-4 w-4 transition group-hover:translate-x-0.5" />
                </Link>

                <Link
                  v-if="canRegister"
                  :href="register()"
                  class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                  create account
                </Link>
              </template>
            </div>

            <div class="mt-6 rounded-xl bg-slate-50 p-4 text-xs text-slate-500">
              for account issues, please contact the clinic administrator.
            </div>
          </div>

          <p class="mt-4 text-center text-xs text-slate-400">
            © {{ new Date().getFullYear() }} sniffs & licks veterinary clinic
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* soft animated blobs (light mode) */
.blob {
  position: absolute;
  width: 520px;
  height: 520px;
  border-radius: 9999px;
  filter: blur(80px);
  opacity: 0.6;
  animation: float 14s ease-in-out infinite;
}

.blob-1 {
  top: -160px;
  left: -180px;
  background: radial-gradient(circle, #a7f3d0, transparent 60%);
}

.blob-2 {
  bottom: -180px;
  right: -200px;
  background: radial-gradient(circle, #bae6fd, transparent 60%);
  animation-delay: -6s;
}

@keyframes float {
  0%, 100% { transform: translate(0,0); }
  50% { transform: translate(24px,-20px); }
}

/* subtle paper grain */
.noise {
  position: absolute;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='140' height='140'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='2'/%3E%3C/filter%3E%3Crect width='140' height='140' filter='url(%23n)' opacity='.05'/%3E%3C/svg%3E");
  pointer-events: none;
}
</style>
