library ieee;
	use ieee.std_logic_1164.all;
--	use ieee.numeric_std.all;

entity fullAdder is
	-- Full, 1 bit adder
	port(
		a, b, c: in  std_logic;
		r, s   : out std_logic
	);
end entity fullAdder;
architecture rtl of fullAdder is
	signal s1, c1, c2 : std_logic;
	component halfAdder
		port(
			a, b : in  std_logic;
			s, c : out std_logic
		);
	end component halfAdder;
begin
	i1: halfAdder port map(a=>a , b=>b, s=>s1, c=>c1);
	i2: halfAdder port map(a=>s1, b=>c, s=>s , c=>c2 );
	r <= c1 or c2;
end architecture rtl;
