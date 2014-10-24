library ieee;
	use ieee.std_logic_1164.all;
--	use ieee.numeric_std.all;

entity halfAdder is
	-- Half, 1 bit adder
	port(
		a, b : in  std_logic;
		s, c : out std_logic
	);
end entity halfAdder;
architecture rtl of halfAdder is
begin
	s <= a xor b;
	c <= a and b;
end architecture rtl;
