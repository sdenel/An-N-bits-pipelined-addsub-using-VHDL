library ieee;
	use ieee.std_logic_1164.all;
	use ieee.numeric_std.all;

entity tb_addsub_rtl is end;

architecture bench of tb_addsub_rtl is
	constant CLK_PER: time      :=  20 ns;
	constant NBITS  : natural   :=  8;
	signal clk      : std_logic := '0';
	signal rst, sub : std_logic;
	signal a, b, z0, z1, z2  : std_logic_vector(NBITS-1 downto 0);
begin
	UUT0: entity work.addsub(rtl)
		generic map(
			NBITS=> NBITS,
			NPIPELINE=> 0, -- 0, 1, 2...
			ISRESET=> '0'
		)
	port map (clk, rst, sub, a, b, z0);
	UUT1: entity work.addsub(rtl)
		generic map(
			NBITS=> NBITS,
			NPIPELINE=> 1, -- 0, 1, 2...
			ISRESET=> '0'
		)
	port map (clk, rst, sub, a, b, z1);
	UUT2: entity work.addsub(rtl)
		generic map(
			NBITS=> NBITS,
			NPIPELINE=> 2, -- 0, 1, 2...
			ISRESET=> '0'
		)
	port map (clk, rst, sub, a, b, z2);
	clk <= not clk after CLK_PER/2;
	rst <= '0', '1' after CLK_PER/4, '0' after 3*CLK_PER/4;
	process
	begin
		wait for 3*CLK_PER/4; -- z doit être 31-12=19
		a <= std_logic_vector(to_unsigned(31, a'length));
		b <= std_logic_vector(to_unsigned(12, a'length));
		sub <= '1';
		wait until rising_edge(clk);
		
		wait for 3*CLK_PER/4; -- z doit être 35+10=45
		a <= std_logic_vector(to_unsigned(35, a'length));
		b <= std_logic_vector(to_unsigned(10, a'length));
		sub <= '0';
		wait until rising_edge(clk);
		
		wait for 3*CLK_PER/4; -- z doit être 0+0=0
		a <= std_logic_vector(to_unsigned(0, a'length));
		b <= std_logic_vector(to_unsigned(0, a'length));
		sub <= '0';
		wait until rising_edge(clk);
	wait;
	end process;
end architecture bench;

