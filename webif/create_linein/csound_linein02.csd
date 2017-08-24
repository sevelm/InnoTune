<CsoundSynthesizer>
<CsOptions>
-+rtaudio=alsa -b 512 -B4096 -iadc:plug:dsnoop02 -odac:plug:LineIn02
</CsOptions>
<CsInstruments>
sr = 44100
ksmps = 64
nchnls = 2
0dbfs=1

instr 1
a1 inch 1
outs a1, a1
endin

</CsInstruments>
<CsScore>
i1 0 3600
</CsScore>
</CsoundSynthesizer>
