quit -sim
cd /YOUR_WORKING_DIRECTORY/VSIM
vcom -check_synthesis *.vhd
vsim work.tb_addsub_rtl(bench)
add wave -position end  sim:/tb_addsub_rtl/clk
add wave -position end  sim:/tb_addsub_rtl/rst
add wave -position end  sim:/tb_addsub_rtl/sub
add wave -position end  -radix binary sim:/tb_addsub_rtl/a
add wave -position end  -radix decimal sim:/tb_addsub_rtl/a
add wave -position end  -radix binary sim:/tb_addsub_rtl/b
add wave -position end  -radix decimal sim:/tb_addsub_rtl/b

add wave -divider {Additionneur/Diviseur avec 0 registre de pipeline}
add wave -position end  -radix binary sim:/tb_addsub_rtl/z0
add wave -position end  -radix decimal sim:/tb_addsub_rtl/z0

add wave -divider {Additionneur/Diviseur avec 1 registres de pipeline}
add wave -position end  -radix binary sim:/tb_addsub_rtl/z1
add wave -position end  -radix decimal sim:/tb_addsub_rtl/z1

add wave -divider {Additionneur/Diviseur avec 1+2=3 registres de pipeline}
add wave -position end  -radix binary sim:/tb_addsub_rtl/z2
add wave -position end  -radix decimal sim:/tb_addsub_rtl/z2
run 500ns

