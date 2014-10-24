#An N bits pipelined addsub using VHDL#
VHDL is a powerful HDL language, with implicit preprocessor functionalities allowing generic designs. Below is an example of source code generating an N-bits addsub with as many pipes as desired using functions and the "generate" keyword. Annoyed by explanations?

##Principle and illustrations##
See https://github.com/sdenel/An-N-bits-pipelined-addsub-using-VHDL/wiki

#Simulation with ModelSim##
Using the do.tcl file described below, you can verify that this module works for different configurations:
![ModelSim screenshot](https://raw.githubusercontent.com/sdenel/An-N-bits-pipelined-addsub-using-VHDL/master/img/vsim.png)

##Source code##
This module does not include any state-machine (the output is entirely defined by the input), thus does not need any reset.

 - The top module is addsub
 - The associated testbench is tb_addsub.vhd
 - You can launch the testbench under ModelSim using ModelSim_launcher.tcl
