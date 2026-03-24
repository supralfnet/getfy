<script setup>
import { Zap } from 'lucide-vue-next';
import {
    TooltipRoot,
    TooltipTrigger,
    TooltipContent,
    TooltipPortal,
    TooltipProvider,
} from 'radix-vue';
</script>

<template>
    <TooltipProvider :delay-duration="250">
        <TooltipRoot>
            <TooltipTrigger as-child>
                <span
                    role="img"
                    aria-label="Liquidação D mais zero, mesmo dia útil"
                    class="inline-flex shrink-0 items-center gap-0.5 rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white shadow-[0_2px_14px_-3px_rgba(234,88,12,0.65),inset_0_1px_0_rgba(255,255,255,0.25)] ring-2 ring-orange-200/90 dark:ring-orange-400/35 bg-gradient-to-br from-[#c2410c] via-[#ea580c] to-[#fbbf24]"
                >
                    <Zap
                        class="h-2.5 w-2.5 shrink-0 fill-amber-100 text-amber-50"
                        aria-hidden="true"
                        :stroke-width="2.5"
                    />
                    D+0
                </span>
            </TooltipTrigger>
            <TooltipPortal>
                <TooltipContent
                    side="top"
                    :side-offset="6"
                    class="max-w-[15rem] rounded-xl border border-zinc-700/80 bg-zinc-950 px-3 py-2 text-left text-xs font-medium leading-snug text-zinc-100 shadow-xl dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100"
                >
                    <span class="block text-[10px] font-semibold uppercase tracking-wider text-amber-400/95">
                        CajuPay
                    </span>
                    <span class="mt-0.5 block text-zinc-300">
                        Liquidação <strong class="text-white">D+0</strong> no mesmo dia útil — fluxo de caixa alinhado ao PIX.
                    </span>
                </TooltipContent>
            </TooltipPortal>
        </TooltipRoot>
    </TooltipProvider>
</template>
